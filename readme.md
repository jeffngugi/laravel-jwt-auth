<h1> Laravel 5.8 API boilerplate with JWT</h1>
<p>This is a laravel API boilerplate with a fully set up JWT for token and middleware</p>
<h3>Routes in </h3>
<p>+--------+----------+-------------------+------+-------------------------------------------------+----------------+</p>
<p>| Domain | Method   | URI               | Name | Action                                          | Middleware     |</p>
<p>+--------+----------+-------------------+------+-------------------------------------------------+----------------+</p>
<p>|        | GET|HEAD | /                 |      | Closure                                         | web            |</p>
<p>|        | POST     | api/auth/login    |      | App\Http\Controllers\AuthController@login       | api            |</p>
<p>|        | POST     | api/auth/logout   |      | App\Http\Controllers\AuthController@logout      | api,jwt.verify |</p>
<p>|        | POST     | api/auth/me       |      | App\Http\Controllers\AuthController@me          | api,jwt.verify |</p>
<p>|        | POST     | api/auth/refresh  |      | App\Http\Controllers\AuthController@refresh     | api,jwt.verify |</p>
<p>|        | POST     | api/auth/register |      | App\Http\Controllers\AuthController@register    | api            |</p>
<p>|        | GET|HEAD | api/auth/verify   |      | App\Http\Controllers\AuthController@emailVerify | api,jwt.verify |</p>
<p>+--------+----------+-------------------+------+-------------------------------------------------+----------------+</p>

<p>To protect a route, Use jwt.verify middleware</p>
