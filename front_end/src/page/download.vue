<template>
    <div>
        download
    </div>
</template>

<script>
    import { baseUrl } from '../config/env';
    export default {
        name: "download",
        mounted() {
            this.location();
        },
        methods: {
            location() {
                let urlLocation = baseUrl + "ExcelController/download";
                window.location.href = urlLocation;
            }
        }
    }
</script>

<style scoped>

</style>