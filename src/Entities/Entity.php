<?php

namespace PhpTelegramBot\Core\Entities;

abstract class Entity implements \JsonSerializable
{
    protected array $fields = [];

    /**
     * @return array<string, class-string>
     */
    protected static function subEntities(): array
    {
        return [];
    }

    public function __construct(
        array $data = []
    ) {
        foreach ($data as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    public function __get(string $name)
    {
        return $this->fields[$name];
    }

    public function __set(string $name, $value): void
    {
        $this->fields[$name] = $value;
    }

    protected function getField(string $name): mixed
    {
        $data = $this->fields[$name];

        $subEntities = static::subEntities();
        if (! isset($subEntities[$name])) {
            return $data;
        }

        if ($subEntities[$name] instanceof Entity) {
            $class = $subEntities[$name];

            return new $class($data);
        } elseif (is_array($subEntities[$name]) && $subEntities[$name][0] instanceof Entity) {
            // Arrays like PhotoSize[]
            $class = $subEntities[$name][0];

            return array_map(fn ($item) => new $class($item), $data);
        } elseif (is_array($subEntities[$name]) && is_array($subEntities[$name][0]) && $subEntities[$name][0][0] instanceof Entity) {
            // Edge case currently only needed for Keyboards: Array of Array of InlineKeyboardButton
            $class = $subEntities[$name][0][0];

            return array_map(fn ($row) => array_map(fn ($button) => new $class($button), $row), $data);
        }

        return $data;
    }

    protected function setField(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
    }

    public function __call(string $name, array $arguments)
    {
        $snakeName = strtolower(ltrim(preg_replace('/[[:upper:]]/', '_$0', $name), '_'));

        if (str_starts_with($snakeName, 'get_')) {
            return $this->getField(substr($snakeName, 4));
        } elseif (str_starts_with($snakeName, 'set_')) {
            $this->setField(substr($snakeName, 4), $arguments[0] ?? null);

            return $this;
        }

        $method = get_class($this).'::'.$name.'()';
        throw new \BadMethodCallException("Call to undefined method $method");
    }

    public function jsonSerialize(): mixed
    {
        return $this->fields;
    }
}
