@extends('tpl.main')
@section('title', 'Illness Simulator')
@section('content')
    <ui-nav></ui-nav>

    <ui-sidenav v-ref:sidenav>
        <form-config v-ref:config></form-config>
    </ui-sidenav>

    <ui-stats></ui-stats>

    <ui-loader v-ref:loader></ui-loader>

    <div id="network" style="height: 100vh;" @click="this.$refs.sidenav.hide()"></div>
@stop