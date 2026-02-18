<?php

namespace Core;

abstract class Model
{
    protected static $table = null;
    protected static $primaryKey = 'id';
    protected static $fillable = [];

    public static function query()
    {
        $table = static::$table ?? static::guessTableName();
        return DB::table($table);
    }

    public static function all()
    {
        return static::query()->get();
    }

    public static function find($id)
    {
        return static::query()->where(static::$primaryKey, $id)->first();
    }

    public static function where($column, $operator, $value = null)
    {
        return static::query()->where($column, $operator, $value);
    }

    public static function create($data)
    {
        // Sadece fillable olanları kabul et
        $filtered = array_intersect_key($data, array_flip(static::$fillable));
        return static::query()->insert($filtered);
    }

    public static function update($id, $data)
    {
        $filtered = array_intersect_key($data, array_flip(static::$fillable));
        return static::query()->where(static::$primaryKey, $id)->update($filtered);
    }

    public static function destroy($id)
    {
        return static::query()->where(static::$primaryKey, $id)->delete();
    }

    protected static function guessTableName()
    {
        $className = basename(str_replace('\\', '/', get_called_class()));
        return strtolower($className) . 's';
    }

    public static function __callStatic($method, $parameters)
    {
        return static::query()->$method(...$parameters);
    }
}
