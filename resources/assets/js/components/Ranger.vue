<template>
    <div class="input-field">
		<div class="ui-ranger" start="15,30" min="0" max="100" step="1" decimals="1"></div>

		<div class="row">
			<div class="input-field mt-0 s6 col">
				<label for="test1">Ranger</label>
				<input type="number" id="test1" name="min" class="ta-c">
			</div>
			<div class="input-field mt-0 s6 col">
				<input type="number" name="max" class="ta-c">
			</div>
		</div>
	</div>
</template>

<script>
    export default {
        ready() {
        }
    }
</script>