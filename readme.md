<h1> Laravel 5.8 API boilerplate with JWT</h1>
<p>This is a laravel API boilerplate with a fully set up JWT for token and middleware</p>
<h3>Routes in </h3>
<table>
    <tr>
        <td>Method</td>
        <td>URI </td>
        <td>Action </td>
        <td> Middleware</td>
    </tr>
    <tr>
        <td>GET|HEAD</td>
        <td>/ </td>
        <td>Closure </td>
        <td> web</td>
    </tr>
     <tr>
        <td>POST</td>
        <td>api/auth/login </td>
        <td>App\Http\Controllers\AuthController@login </td>
        <td> api</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>api/auth/logout </td>
        <td>App\Http\Controllers\AuthController@logout </td>
        <td> jwt.verify</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>api/auth/me </td>
        <td>App\Http\Controllers\AuthController@me </td>
        <td> jwt.verify</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>api/auth/refresh </td>
        <td>App\Http\Controllers\AuthController@refresh </td>
        <td> jwt.verify</td>
    </tr>
     <tr>
        <td>POST</td>
        <td>api/auth/register </td>
        <td>App\Http\Controllers\AuthController@register </td>
        <td> jwt.verify</td>
    </tr>
    </table>
           

<p>To protect a route, Use jwt.verify middleware</p>
