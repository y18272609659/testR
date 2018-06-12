<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>帮助 - 繁盛王国</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/app.css?v='.time()) }}">
</head>
<body>

<h1>Wiki - 这玩意儿也就是你查资料用的，当作教程看的，要么是大佬，要么是傻子</h1>

<h2>资源</h2>
<p>占用性资源在建筑拆除、兵役遣返后，会被返还；消耗性资源则会被永久消耗，建筑在拆除时返还部分。</p>
<ol>
    <li>人口：占用性资源，无论是建立工坊还是战火纷飞，人，都是真正核心的角色。</li>
    <li>木材：消耗性资源，从简陋的众多民舍，到城头的无穷滚木，木材因为其易于生产以及绝大多数时刻的可靠性，被广泛应用。</li> {{-- todo: 补充 Wiki --}}
</ol>

<h2>建筑</h2>
<p>简单分为民用类、军事类，民用类建筑不涉及任何军用内容，军用类建筑可能具备民用功能，但在战争中可以提供一定的加成。(0.1 ver)</p>
</body>

</html>
