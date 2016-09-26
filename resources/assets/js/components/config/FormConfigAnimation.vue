
<template>

	<div class="collapsible-header">Animação</div>
	<div class="collapsible-body pl pr">
		<ui-slider
			name="animation[scale]"
			label="Escala de tempo"
			start="0.5"
			step="0.1"
			min="0"
			max="2"
			decimals="1"
		></ui-slider>

		<ul class="collapsible popout mt-0" data-collapsible="accordion">

			<li class="collection-item">

				<div class="collapsible-header">
					<i class="fa fa-square red-text" aria-hidden="true"></i> Infecção
				</div>
				<div class="collapsible-body pl pr">

					<ui-slider
						name="animation[infect][restTime]"
						label="Tempo de pausa (s)"
						start="0.5"
						step="0.1"
						min="0"
						max="10"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[infect][dotMovimentTime]"
						label="Duração de movimento (s)"
						start="0.6"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[infect][expandTime]"
						label="Duração de expansão (s)"
						start="0.6"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[infect][expandDelay]"
						label="Atraso de expansão (s)"
						start="0.5"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>
					
					<ui-slider
						name="animation[infect][expandScale]"
						label="Escala de expansão"
						start="1.5"
						step="0.1"
						min="1"
						max="2"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[infect][retractTime]"
						label="Duração de retração (s)"
						start="0.4"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[infect][shakeRadius]"
						label="Raio de vibração"
						start="15"
						step="0.1"
						min="0"
						max="30"
						decimals="1"
						postfix=""
					></ui-slider>

				</div>

			</li>

			<li class="collection-item">

				<div class="collapsible-header">
					<i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperação
				</div>
				<div class="collapsible-body pl pr">

					<ui-slider
						name="animation[recover][restTime]"
						label="Tempo de pausa (s)"
						start="0.5"
						step="0.1"
						min="0"
						max="10"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[recover][expandTime]"
						label="Duração de expansão (s)"
						start="0.4"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[recover][expandScale]"
						label="Escala de expansão"
						start="1.3"
						step="0.1"
						min="1"
						max="2"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[recover][retractTime]"
						label="Duração de retração (s)"
						start="2"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>



				</div>

			</li>

			<li class="collection-item">

				<div class="collapsible-header">
					<i class="fa fa-square black-text" aria-hidden="true"></i> Morte
				</div>
				<div class="collapsible-body pl pr">

					<ui-slider
						name="animation[death][restTime]"
						label="Tempo de pausa (s)"
						start="0.5"
						step="0.1"
						min="0"
						max="10"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][retractScale]"
						label="Escala de retração"
						start="0.6"
						step="0.1"
						min="0"
						max="1"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][retractTime]"
						label="Duração de retração (s)"
						start="2.5"
						step="0.1"
						min="0"
						max="4"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][edgeRetractTime]"
						label="Duração de retração (aresta) (s)"
						start="1"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][edgeRetractSize]"
						label="Tamanho de retração (aresta)"
						start="0"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][edgeRetractDelay]"
						label="Atraso de retração (aresta) (s)"
						start="1"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][birthExpandTime]"
						label="Duração de expansão (nascimento) (s)"
						start="0.4"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][birthExpandDelay]"
						label="Atraso de expansão (nascimento) (s)"
						start="2"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][birthExpandScale]"
						label="Escala de expansão (nascimento)"
						start="1.3"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

					<ui-slider
						name="animation[death][birthRetractTime]"
						label="Duração de retração (nascimento) (s)"
						start="2"
						step="0.1"
						min="0"
						max="3"
						decimals="1"
						postfix=""
					></ui-slider>

				</div>
				
			</li>

	    </ul>
	</div>

</template>

<script>
    export default {

        ready() {

			
        }
    }
</script>