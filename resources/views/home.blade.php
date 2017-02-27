@extends('tpl.main')
@section('title', 'Illness Simulator')
@section('content')

    <ui-nav></ui-nav>

    <ui-sidenav v-ref:sidenav>
        <form-config v-ref:config></form-config>
    </ui-sidenav>

    <network v-ref:network></network>

@stop