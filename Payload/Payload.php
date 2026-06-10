<?php

declare(strict_types=1);

namespace PhpWebsocketRpc\Rpc\Payload;

abstract class Payload
{
    private(set) string $id;

    protected function __construct()
    {
        $this->id = \bin2hex(\random_bytes(16));
    }

    protected function initWithId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return class-string<Payload>|null
     */
    public static function responseClass(): ?string
    {
        return null;
    }

    final public function toArray(): array
    {
        $ref = new \ReflectionClass($this);
        $data = ['id' => $this->id];

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($prop->isStatic() || $prop->getName() === 'id') {
                continue;
            }

            $data[$prop->getName()] = self::serializeValue($prop->getValue($this));
        }

        return [static::class, $data];
    }

    private static function serializeArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = self::serializeValue($value);
        }
        return $result;
    }

    private static function serializeValue(mixed $value): mixed
    {
        return match (true) {
            $value instanceof Payload => $value->toArray(),
            $value instanceof \BackedEnum => $value->value,
            $value instanceof \UnitEnum => $value->name,
            \is_object($value) && \method_exists($value, 'toArray') => $value->toArray(),
            \is_array($value) => self::serializeArray($value),
            default => $value,
        };
    }

    public static function fromArray(array $data): static
    {
        $class = static::class;
        $ref = new \ReflectionClass($class);
        $constructor = $ref->getConstructor();

        if ($constructor === null) {
            throw new \RuntimeException(\sprintf('Payload class %s must have a constructor', $class));
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();

            if (!\array_key_exists($name, $data)) {
                if ($param->isDefaultValueAvailable()) {
                    $params[] = $param->getDefaultValue();
                } else {
                    throw new \RuntimeException(\sprintf(
                        'Missing required constructor parameter "$%s" for %s',
                        $name,
                        $class,
                    ));
                }
            } else {
                $params[] = self::decodeValue($data[$name], $param->getType());
            }
        }

        /** @var static $instance */
        $instance = $ref->newInstance(...$params);

        // If the ID was passed but not as a constructor param, set it via initWithId
        if (\array_key_exists('id', $data) && $instance->id !== $data['id']) {
            $instance->initWithId($data['id']);
        }

        return $instance;
    }

    private static function decodeValue(mixed $value, ?\ReflectionType $type): mixed
    {
        // Reconstruct enum from expected type if available
        if ($type !== null && $type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();

            if (\is_subclass_of($typeName, \BackedEnum::class)) {
                return $typeName::from($value);
            }

            if (\is_subclass_of($typeName, \UnitEnum::class)) {
                $const = $typeName . '::' . $value;
                if (\defined($const)) {
                    return \constant($const);
                }
                return $value;
            }
        }

        // [FQCN, props] — recursively decode
        if (\is_array($value) && \array_is_list($value) && \count($value) === 2 && \is_string($value[0])) {
            [$fqcn, $props] = $value;
            if (\is_subclass_of($fqcn, self::class)) {
                return $fqcn::fromArray($props);
            }
        }

        if (\is_array($value)) {
            $decoded = [];
            foreach ($value as $k => $v) {
                $decoded[$k] = self::decodeValue($v, null);
            }
            return $decoded;
        }

        return $value;
    }
}
