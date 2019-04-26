<template>
    <div id="app">
        <router-view class="app-router"></router-view>
    </div>
</template>

<script>
export default {

}
</script>

<style>
    #app {
        position: absolute;
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
    }

    .app-router {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /*禁用input type=number css style*/
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button
    {
        display: none;
    }
</style>
