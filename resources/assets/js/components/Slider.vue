
<template>
    <div id="field" class="input-field">
        <div class="ui-slider" start="1" min="0" max="2" step="0.1" decimals="1"></div>
        <label for="scale">Slider</label>
        <input type="number" min="0" max="2" step="0.1" id="scale" name="scale" class="ta-c">
    </div>
</template>

<script>
    export default {
        ready() {
        }
    }
</script>