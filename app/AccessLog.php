<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AccessLog.
 *
 * @property int         $access_id
 * @property int         $student_id
 * @property string      $session_id
 * @property string|null $user_ip
 * @property int|null    $screen_width
 * @property int|null    $screen_height
 * @property string|null $user_agent
 * @property string|null $OS
 * @property string      $url
 * @property string      $timestamp
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereAccessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereScreenHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereScreenWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog whereUserIp($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccessLog query()
 */
class AccessLog extends Model
{
    public $timestamps = false;

    protected $table = 'accesslog';
}
