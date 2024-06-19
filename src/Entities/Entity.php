<?php

namespace PhpTelegramBot\Core\Entities;

use BadMethodCallException;
use JsonSerializable;
use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

abstract class Entity implements JsonSerializable
{
    protected array $fields = [];

    public function __construct(
        array $data = []
    ) {
        foreach ($data as $key => $value) {
            $this->fields[$key] = $value;
        }

        foreach (static::presetData() as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    public function __get(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->fields[$name] = $value;
    }

    public function __call(string $name, array $arguments)
    {
        $snakeName = strtolower(ltrim(preg_replace('/[[:upper:]]/', '_$0', $name), '_'));

        if (str_starts_with($snakeName, 'get_')) {
            // Getter for fields
            return $this->getField(substr($snakeName, 4));
        } elseif (str_starts_with($snakeName, 'set_')) {
            // Setter for fields
            $this->setField(substr($snakeName, 4), $arguments[0] ?? null);

            return $this;
        } elseif (is_subclass_of($this, AllowsBypassingGet::class)) {
            $fields = $this::fieldsBypassingGet();

            if (array_key_exists($snakeName, $fields)) {

                return $this->getField($snakeName) ?? $fields[$snakeName];

            } elseif (in_array($snakeName, $fields)) {

                return $this->getField($snakeName);

            }
        }

        $method = get_class($this) . '::' . $name . '()';
        throw new BadMethodCallException("Call to undefined method $method");
    }

    private function getField(string $name): mixed
    {
        $data = $this->fields[$name] ?? null;

        $subEntities = static::subEntities();
        if (is_null($data) || ! isset($subEntities[$name])) {
            return $data;
        }

        if (is_subclass_of($subEntities[$name], Entity::class)) {
            $class = $subEntities[$name];

            return new $class($data);
        } elseif (is_array($subEntities[$name]) && is_subclass_of($subEntities[$name][0], Entity::class)) {
            // Arrays like PhotoSize[]
            $class = $subEntities[$name][0];

            return array_map(fn ($item) => new $class($item), $data);
        } elseif (is_array($subEntities[$name]) && is_array($subEntities[$name][0]) && is_subclass_of($subEntities[$name][0][0], Entity::class)) {
            // Edge case currently only needed for Keyboards: Array of Array of InlineKeyboardButton
            $class = $subEntities[$name][0][0];

            return array_map(fn ($row) => array_map(fn ($button) => new $class($button), $row), $data);
        }

        return $data;
    }

    /**
     * @return array<string, class-string>
     */
    protected static function subEntities(): array
    {
        return [];
    }

    protected static function presetData(): array
    {
        return [];
    }

    private function setField(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter($this->fields, fn ($value) => ! is_null($value));
    }
}
