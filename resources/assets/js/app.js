/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('ui-slider', require('./components/Slider.vue'));
Vue.component('ui-ranger', require('./components/Ranger.vue'));

const app = new Vue({
	el: 'body'
});

/**
 * Initial Configuration
 */
var container = document.getElementById('network');
var canvas;
var context;

var network;

var nodes;
var edges;

var illsim;
var options;

/**
 * Initialization
 */

$(function() {
	//init();

	var slider_init = function() {

		var ranger = this.className === 'ui-ranger';

		var slider = this;
		var field = $(slider).parent();
		var input = field.find('input');
		var label = field.find('label');

		var start = $(slider).attr('start');
		var step = $(slider).attr('step');
		var min = $(slider).attr('min');
		var max = $(slider).attr('max');
		var decimals = $(slider).attr('decimals');
		var postfix = $(slider).attr('postfix');

		var start_ranger = start.split(",");

		label.css('top', 0);
		label.css('font-size', '14px');
		input.css('border', 'none');

		noUiSlider.create(slider, {
			start: ranger ? start_ranger : (start ? Number(start) : 0),
			connect: ranger ? true : 'lower',
			step: step ? Number(step) : 1,
			range: {
				'min': min ? Number(min) : 0,
				'max': max ? Number(max) : 100
			},
			format: wNumb({
				decimals: decimals ? Number(decimals) : 0,
				postfix: postfix ? postfix : ""
			})
		});

		slider.noUiSlider.on('update', function(values, handle) {
			input.each(function(index) {
				$(this).val(values[index]);
			});
		});

		input.each(function(index) {
			$(this).on('change', function() {
				if (index)
					slider.noUiSlider.set([null, this.value]);
				else
					slider.noUiSlider.set([this.value, null]);
			});
		});

	}

	$('.ui-slider').each(slider_init);
	$('.ui-ranger').each(slider_init);

	$('select').material_select();
});

/**
 *  Main Functions
 */

function init() {
	nodes = new vis.DataSet();
	edges = new vis.DataSet();

	$.when(
		$.getJSON("/data/Illsim.json", function(data) {
			illsim = data;
		}),
		$.getJSON("/data/Settings.json", function(data) {
			options = data;
		})
	).then(function() {
		$.getJSON("/data/FactoryFullRandom.json", factoryFullRandom);
		//$.getJSON("/data/FactoryUniformFormat.json", factoryUniformFormat);
	});

}

function createNetwork(data) {

	network = new vis.Network(container, normalize(data), options);

	canvas = document.getElementsByTagName("canvas")[0];
	context = canvas.getContext("2d");

	network.once('initRedraw', function() {

	});

	network.once('afterDrawing', function() {
		$('.loading').hide();
		setInterval(attempt, illsim.simulation.step);
	});

	function normalize(data) {

		const BORDER_WIDTH_SCALE = 8;
		const NODE_SIZE_SCALE = 15;
		const EDGE_WIDTH_SCALE = 10;

		$.each(data.nodes, function(index, node) {
			node.base = node.size = options.nodes.size;
			node.neighbors = getNeighbors(node);

			node.borderWidth = node.borderWidthSelected = options.nodes.borderWidth;
			node.borderWidth = node.borderWidthSelected += node.rate.resist * BORDER_WIDTH_SCALE;

			if (illsim.simulation.infectBy === 'node' || illsim.simulation.infectBy === "both") {
				node.label = (Math.round(node.rate.infect * 1000) / 10) + "%";
				node.base = node.size += node.rate.infect * NODE_SIZE_SCALE;
			}
		});

		$.each(data.edges, function(index, edge) {
			edge.width = options.edges.width;

			if (illsim.simulation.infectBy === 'edge' || illsim.simulation.infectBy === "both") {
				edge.label = (Math.round(edge.rate.infect * 1000) / 10) + "%";
				edge.width += edge.rate.infect * EDGE_WIDTH_SCALE;
			}
		});

		nodes = new vis.DataSet(data.nodes);
		edges = new vis.DataSet(data.edges);

		/**
		 * return indexes (eg. [1,2,3])
		 */
		function getNeighbors(node) {
			var neighbors = [];
			$.each(data.edges, function(index, edge) {
				if (edge.from === node.id)
					neighbors.push(edge.to);
				else if (edge.to === node.id)
					neighbors.push(edge.from);
			});
			return neighbors;
		}

		return {
			nodes: nodes,
			edges: edges
		};
	}
}

/**
 * Useful functions
 */

function getEdge(node1, node2) {
	return edges.get({
		filter: function(edge) {
			return (edge.from === node1.id && edge.to === node2.id) || (edge.from === node2.id && edge.to === node1.id);
		}
	});
}

function nodesGroup(group) {
	return nodes.get({
		filter: function(node) {
			return node.group === group;
		}
	});
}

function setAnimation(target, time) {
	target.animating = true;
	nodes.update(target);

	TweenMax.delayedCall(time * illsim.animation.scale, function() {
		target.animating = false;
		nodes.update(target);
	});
}

function group(node) {
	return eval("options.groups." + node.group + ";");
}

/**
 * Attempts
 */

function attempt() {
	nodes.forEach(function(node) {
		infectAttempt(node);
		recoverAttempt(node);
		killAttempt(node);
	});
}

function infectAttempt(infected) {

	if (infected.animating)
		return false;

	var neighbors = nodes.get(infected.neighbors);
	neighbors.forEach(eachNeighbors);

	function eachNeighbors(target) {

		if (allowed())
			switch (illsim.simulation.infectBy) {
				case "node":
					infectByNode();
					break;
				case "edge":
					infectByEdge();
					break;
				case "both":
					infectByBoth();
					break;
				default:
					infectByEdge();
			}

		function allowed() {
			if (target.animating)
				return false;

			if (target.group === "i")
				return false;

			if (target.group === "d")
				return false;

			return true;
		}

		function infectByNode() {

			if (Math.random() > group(target).rateBase.resist)
				if (Math.random() > target.rate.resist)
					if (Math.random() < infected.rate.infect * group(infected).rateBase.infect)
						infect_ani();
		}

		function infectByEdge() {

			var edge = getEdge(infected, target)[0];
			if (edge == undefined) return;

			if (Math.random() > group(target).rateBase.resist)
				if (Math.random() > target.rate.resist)
					if (Math.random() < edge.rate.infect * group(infected).rateBase.infect)
						infect_ani();
		}

		function infectByBoth() {

			var edge = getEdge(infected, target)[0];
			if (edge == undefined) return;

			if (Math.random() > group(target).rateBase.resist)
				if (Math.random() > target.rate.resist)
					if (Math.random() < infected.rate.infect * edge.rate.infect * group(infected).rateBase.infect)
						infect_ani();
		}

		function infect_ani() {

			const REST_TIME = 2.45;
			//
			const DOT_MOVIMENT_TIME = .6;
			const INFECT_EXPAND_TIME = .6;
			const INFECT_EXPAND_DELAY = .5;
			const INFECT_EXPAND_SCALE = 1.5;
			const INFECT_RETRACT_TIME = .4;
			const INFECT_ACTIVE_DELAY = 2;

			setAnimation(infected, REST_TIME);
			setAnimation(target, REST_TIME);

			var infected_pos = network.getPositions([infected.id])[infected.id];
			var target_pos = network.getPositions([target.id])[target.id];

			var dot = {
				x: infected_pos.x,
				y: infected_pos.y,
				draw: function() {
					context.beginPath();
					context.arc(this.x, this.y, 6, 0, 2 * Math.PI, false);
					context.fillStyle = 'black';
					context.fill();
				}
			};

			_.bindAll(dot, 'draw');

			network.on('afterDrawing', dot.draw);

			//Dot moviment animation
			TweenMax.to(dot, DOT_MOVIMENT_TIME * illsim.animation.scale, {
				x: target_pos.x,
				y: target_pos.y,
				ease: Power2.easeOut,
				onUpdate: function() {
					network.redraw();
				}
			});

			//Node expand animation
			TweenMax.to(target, INFECT_EXPAND_TIME * illsim.animation.scale, {
				delay: INFECT_EXPAND_DELAY * illsim.animation.scale,
				size: target.base * INFECT_EXPAND_SCALE,
				ease: Power2.easeOut,
				onUpdate: function() {
					nodes.update(target);
				},
				onComplete: function() {
					network.off('afterDrawing', dot.draw);

					target.group = "i";
					nodes.update(target);

					//Shake it!
					var hip = illsim.animation.shakeRadius;
					var angle = Math.atan2(target_pos.y - infected_pos.y, target_pos.x - infected_pos.x);
					network.moveNode(
						target.id,
						target_pos.x + Math.cos(angle) * hip,
						target_pos.y + Math.sin(angle) * hip
					);

					//Node retract animation
					TweenMax.to(target, INFECT_RETRACT_TIME * illsim.animation.scale, {
						size: target.base,
						ease: Power3.easeOut,
						onUpdate: function() {
							nodes.update(target);
						}
					});
				}
			});

		}

	}

}

function recoverAttempt(target) {

	if (allowed())
		recover();

	function allowed() {
		if (target.animating)
			return false;

		if (target.group === "r")
			return false;

		if (target.group === "d")
			return false;

		return true;
	}

	function recover() {
		if (Math.random() < target.rate.recover * group(target).rateBase.recover)
			recover_ani();
	}

	function recover_ani() {

		const REST_TIME = 2.35;
		//
		const RECOVER_EXPAND_TIME = .4;
		const RECOVER_EXPAND_SCALE = 1.3;
		const RECOVER_RETRACT_TIME = 2;

		setAnimation(target, REST_TIME);

		target.group = "r";

		TweenMax.to(target, RECOVER_EXPAND_TIME * illsim.animation.scale, {
			size: target.base * RECOVER_EXPAND_SCALE,
			ease: Power2.easeOut,
			onUpdate: function() {
				nodes.update(target);
			},
			onComplete: function() {
				TweenMax.to(target, RECOVER_RETRACT_TIME * illsim.animation.scale, {
					size: target.base,
					ease: Elastic.easeOut.config(1, 0.3),
					onUpdate: function() {
						nodes.update(target);
					}
				});
			}
		});
	}
}

function killAttempt(target) {

	if (allowed())
		kill();

	function allowed() {
		if (target.animating)
			return false;

		if (target.group === "d")
			return false;

		return true;
	}

	function kill() {
		if (Math.random() < target.rate.death * group(target).rateBase.death) {
			death_ani();
		}
	}

	function death_ani() {

		const REST_TIME = 8;
		//
		const DEATH_RETRACT_SCALE = .6;
		const DEATH_RETRACT_TIME = 2.5;
		const EDGE_RETRACT_TIME = 1;
		const EDGE_RETRACT_SIZE = 0;
		const EDGE_RETRACT_DELAY = 1;
		//Birth
		const BIRTH_EXPAND_TIME = .4;
		const BIRTH_EXPAND_DELAY = 2;
		const BIRTH_EXPAND_SCALE = 1.3;
		const BIRTH_RETRACT_TIME = 2;

		setAnimation(target, REST_TIME);

		target.group = "d";

		var edges_id = network.getConnectedEdges(target.id);

		//Removing edge animation
		if (!illsim.simulation.birthWhenDie)
			edges.get(edges_id, {
				filter: function(edge) {
					return !edge.removing;
				}
			}).forEach(edge_ani)

		//Node death animation
		TweenMax.to(target, DEATH_RETRACT_TIME * illsim.animation.scale, {
			size: options.nodes.size * DEATH_RETRACT_SCALE,
			ease: Power1.easeOut,
			onUpdate: function() {
				nodes.update(target);
			},
			onComplete: function() {
				//Birth animation
				if (illsim.simulation.birthWhenDie)
					birth_ani();
			}
		});

		function birth_ani() {
			target.group = "s";
			TweenMax.to(target, BIRTH_EXPAND_TIME * illsim.animation.scale, {
				size: target.base * BIRTH_EXPAND_SCALE,
				ease: Power2.easeOut,
				delay: BIRTH_EXPAND_DELAY * illsim.animation.scale,
				onUpdate: function() {
					nodes.update(target);
				},
				onComplete: function() {
					TweenMax.to(target, BIRTH_RETRACT_TIME * illsim.animation.scale, {
						size: target.base,
						ease: Elastic.easeOut.config(1, 0.3),
						onUpdate: function() {
							nodes.update(target);
						}
					});
				}
			});
		}

		function edge_ani(edge) {

			edge.removing = true;
			edges.update(edge);

			TweenMax.to(edge, EDGE_RETRACT_TIME * illsim.animation.scale, {
				width: EDGE_RETRACT_SIZE,
				delay: EDGE_RETRACT_DELAY * illsim.animation.scale,
				ease: Power1.easeOut,
				onUpdate: function() {
					edges.update(edge);
				},
				onComplete: function() {
					var node1 = nodes.get(edge.from);
					var node2 = nodes.get(edge.to);

					node1.neighbors = _.without(node1.neighbors, node2);
					node2.neighbors = _.without(node2.neighbors, node1);

					nodes.update(node1, node2);
					edges.remove(edge);
				}
			});
		}
	}

}

/**
 * Factories
 */

function factoryUniformFormat(config) {

	createNetwork({
		nodes: nodesFactory().get(),
		edges: edgesFactory().get()
	});

	function nodesFactory() {
		var nodeConfig = config.node;

		for (var i = 0; i < Math.pow(config.level, 2); i++)
			nodes.add({
				id: i,
				group: "s",
				rate: nodeConfig.rate
			});

		//Scan each node group and apply the quantity
		$.each(nodeConfig.groups, function(index, group) {
			for (var i = 0; i < group.quant; i++) {
				var node = _.sample(nodes.get());
				node.group = group.ref;
				nodes.update(node);
			}
		});

		return nodes;
	}

	function edgesFactory() {
		var edgeConfig = config.edge;
		var row = config.level;
		var line = config.level - 1;

		for (var i = 0; i < row; i++)
			for (var j = 0; j < line; j++) {
				var node1 = nodes.get(i * row + j);
				var node2 = nodes.get(i * row + (j + 1));

				edges.add({
					from: node1.id,
					to: node2.id,
					rate: edgeConfig.rate
				});
			}

		for (var i = 0; i < row; i++)
			for (var j = 0; j < line; j++) {
				var node1 = nodes.get(j * row + i);
				var node2 = nodes.get((j + 1) * row + i);

				edges.add({
					from: node1.id,
					to: node2.id,
					rate: edgeConfig.rate
				});
			}

		return edges;
	}
}

function factoryFullRandom(config) {

	createNetwork({
		nodes: nodesFactory().get(),
		edges: edgesFactory().get()
	});

	function nodesFactory() {

		var nodeConfig = config.node;
		var rate = nodeConfig.rate;

		// Get the number of nodes that will be generate
		var quant = Math.round(random(nodeConfig.min, nodeConfig.max));

		//Get a random group left to avoid undefined
		var groupLeft = nodeConfig.groups[Math.floor(Math.random() * nodeConfig.groups.length)];

		//Scan each node group and apply the quantity
		$.each(nodeConfig.groups, function(index, group) {
			if (group.quant === undefined) {
				groupLeft = group;
				return;
			}
			switch (group.quantType) {
				case "percent":
					addNode(group, group.quant / 100 * quant);
					break;

				default:
					addNode(group, group.quant);
					break;
			}
		});

		//Add what is left
		addNode(groupLeft, quant - nodes.length);

		function addNode(group, quant) {
			for (var i = 0; i < quant; i++)
				nodes.add({
					id: nodes.length,
					group: group.ref,
					rate: {
						infect: random(rate.infect.min, rate.infect.max),
						resist: random(rate.resist.min, rate.resist.max),
						recover: random(rate.recover.min, rate.recover.max),
						death: random(rate.death.min, rate.death.max)
					}
				});
		}

		return nodes;
	}

	function edgesFactory() {

		var edgeConfig = config.edge;
		var rate = edgeConfig.rate;

		var quant = Math.round(random(edgeConfig.min, edgeConfig.max));

		//Adds edges
		while (quant > 0) {
			var changed = false;
			nodes.forEach(function(node1) {
				if (quant <= 0) return;
				var pool = nodes.get();
				do {
					var node2 = _.sample(pool); //get a random node
					pool = _.without(pool, node2); //pop node2 from the pool

					if (addEdge(node1, node2)) {
						quant--;
						changed = true;
						break;
					}
				} while (!_.isEmpty(pool)); //Attempt limit
			});
			if (!changed) break;
		}

		function addEdge(node1, node2) {
			if (node1.id === node2.id) return false;

			var neighbors1 = getNeighbors(node1);
			var neighbors2 = getNeighbors(node2);
			var edge = getEdge(node1, node2);

			//The nodes can't exceed the max edges setted
			if (neighbors1.length < config.node.maxEdges && neighbors2.length < config.node.maxEdges)
				if (_.isEmpty(edge)) {
					edges.add({
						from: node1.id,
						to: node2.id,
						rate: {
							infect: random(rate.infect.min, rate.infect.max)
						}
					});
					return true;
				}
			return false;
		}

		function getNeighbors(node) {
			var neighbors = [];
			edges.forEach(function(edge) {
				if (edge.from === node.id)
					neighbors.push(nodes.get(edge.to));
				else if (edge.to === node.id)
					neighbors.push(nodes.get(edge.from));
			});
			return neighbors;
		}

		return edges;
	}

	function random(min, max) {
		return Math.random() * (max - min) + min;
	}

}