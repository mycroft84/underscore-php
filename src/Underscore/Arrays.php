<?php
/**
 * Arrays
 *
 * Helpers and functions for arrays
 */
namespace Underscore;

use \Closure;

class Arrays extends Interfaces\CollectionMethods
{

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// ANALYZE //////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Check if an array has a given key
   */
  public static function has($array, $key)
  {
    return static::get($array, $key, 'UNFOUND') !== 'UNFOUND';
  }

  /**
   * Check if all items in an array match a truth test
   */
  public static function matches($array, Closure $closure)
  {
    // Reduce the array to only booleans
    $array = (array) static::each($array, $closure);

    // Check the results
    if (sizeof($array) === 0) return true;
    $array = array_search(false, $array, false);

    return is_bool($array);
  }

  /**
   * Check if any item in an array matches a truth test
   */
  public static function matchesAny($array, Closure $closure)
  {
    // Reduce the array to only booleans
    $array = (array) static::each($array, $closure);

    // Check the results
    if (sizeof($array) === 0) return true;
    $array = array_search(true, $array, false);

    return is_int($array);
  }

  /**
   * Check if an item is in an array
   */
  public static function contains($array, $value)
  {
    return in_array($value, $array);
  }

   /**
   * Returns the average value of an array
   *
   * @param  array   $array    The source array
   * @param  integer $decimals The number of decimals to return
   * @return integer           The average value
   */
  public static function average($array, $decimals = 0)
  {
    return round((array_sum($array) / sizeof($array)), $decimals);
  }

  /**
   * Get the sum of an array
   */
  public static function sum($array)
  {
    return array_sum($array);
  }

  /**
   * Get the size of an array
   */
  public static function size($array)
  {
    return sizeof($array);
  }

  /**
   * Get the max value from an array
   */
  public static function max($array, $closure = null)
  {
    // If we have a closure, apply it to the array
    if ($closure) $array = Arrays::each($array, $closure);

    // Sort from max to min
    arsort($array);

    return Arrays::first($array);
  }

  /**
   * Get the min value from an array
   */
  public static function min($array, $closure = null)
  {
    // If we have a closure, apply it to the array
    if ($closure) $array = Arrays::each($array, $closure);

    // Sort from max to min
    asort($array);

    return Arrays::first($array);
  }

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// FETCH FROM ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Find the first item in an array that passes the truth test
   */
  public static function find($array, Closure $closure)
  {
    foreach ($array as $value) {
      if ($closure($value)) return $value;
    }

    return $array;
  }

  /**
   * Clean all falsy values from an array
   */
  public static function clean($array)
  {
    return Arrays::select($array, function($value) {
      return (bool) $value;
    });
  }

  /**
   * Fetches all columns $property from a multimensionnal array
   */
  public static function pluck($array, $property)
  {
    foreach ($array as $key => $value) {
      $array[$key] = Arrays::get($value, $property, $value);
    }

    return $array;
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// SLICERS //////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get the first value from an array
   */
  public static function first($array, $take = null)
  {
    if (!$take) return array_shift($array);
    return array_splice($array, 0, $take, true);
  }

  /**
   * Get the last value from an array
   */
  public static function last($array, $take = null)
  {
    if (!$take) return array_pop($array);

    return Arrays::rest($array, -$take);
  }

  /**
   * Get everything but the last $to items
   */
  public static function initial($array, $to)
  {
    $slice = sizeof($array) - $to;

    return Arrays::first($array, $slice);
  }

  /**
   * Get the last elements from index $from
   */
  public static function rest($array, $from = 1)
  {
    return array_splice($array, $from);
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// ACT UPON /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Iterate over an array and execute a callback for each loop
   */
  public static function at($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      $closure($value, $key);
    }

    return $array;
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// ALTER ///////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Iterate over an array and modify the array's value
   */
  public static function each($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      $array[$key] = $closure($value, $key);
    }

    return $array;
  }

  /**
   * Find all items in an array that pass the truth test
   */
  public static function filter($array, Closure $closure)
  {
    return array_filter($array, $closure);
  }

  /**
   * Invoke a function on all of an array's values
   */
  public static function invoke($array, $callable, $arguments = array())
  {
    // If the callable has arguments, pass them
    if ($arguments) return array_map($callable, $array, $callable);
    return array_map($callable, $array);
  }

  /**
   * Return all items that fail the truth test
   */
  public static function reject($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      if (!$closure($value)) $filtered[$key] = $value;
    }

    return $filtered;
  }
}
