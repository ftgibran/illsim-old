@extends('tpl.main')
@section('title', 'IllSim')
@section('content')
    
    <ui-nav></ui-nav>
    
    <ui-sidenav v-ref:sidenav>
        <form-config v-ref:config></form-config>
    </ui-sidenav>

    <ui-loader v-ref:loader></ui-loader>

    <div @click="this.$refs.sidenav.hide()" id="network" style="height:100vh;"></div>
@stop