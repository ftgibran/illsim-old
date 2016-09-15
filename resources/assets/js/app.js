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

const app = new Vue({
	el: 'body'
});

/**
 * Constants
 */

const TIME_SCALE = 1;
const ATTEMPT_INTERVAL = 400;

/**
 * Initial Configuration
 */
var container = document.getElementById('network');
var canvas;
var context;

var network;

var nodes;
var edges;

var options = {
	nodes: {
		borderWidth: 3,
		borderWidthSelected: 3,
		shape: "dot",
		size: 15
	},
	edges: {
		color: "#666666",
		width: 3,
		smooth: false
	},
	interaction: {
		hover: true,
		selectConnectedEdges: false
	},
	"physics": {
		"minVelocity": 0.05
	},
	groups: {
		i: { //Infected
			color: '#D46A6A'
		},
		r: { //Recovered
			color: '#ECC13E'
		},
		s: { //Susceptible
			color: '#b0bec5'
		},
		v: { //Vaccinated
			color: '#85BC5E'
		},
		d: { //Died
			color: '#666666'
		},
		t: { //Transition
			color: '#b0bec5'
		}
	}
}

/**
 * Initialization
 */

$(function() {
	init();
});

/**
 *  Main Functions
 */

function init() {
	//$.getJSON("/data/Example1.json", createNetwork);
	$.getJSON("/data/FactoryFullRandom.json", factoryFullRandom);
}

function createNetwork(data) {
	network = new vis.Network(container, normalize(data), options);

	canvas = document.getElementsByTagName("canvas")[0];
	context = canvas.getContext("2d");

	network.once('initRedraw', function() {

	});

	network.once('afterDrawing', function() {
		$('.loading').hide();
		setInterval(function() {
			infectAttempt();
			recoverAttempt();
			killAttempt();
		}, ATTEMPT_INTERVAL);
	});

	function normalize(data) {
		//Label edges with their rates
		$.each(data.edges, function(index, edge) {
			edge.label = (Math.round(edge.infect.rate * 1000) / 10) + "%";
			edge.width = 3 + edge.infect.rate * 10;
		});

		$.each(data.nodes, function(indes, node) {
			node.neighbors = getNeighbors(node);
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

/**
 * Attempts
 */

function recoverAttempt() {
	nodesGroup("i").forEach(function(infected) {
		recover(infected);
	});

	function recover(infected) {
		if (Math.random() < infected.recover.rate) {
			infected.group = "r";
			nodes.update(infected);
			recover_ani(infected);
		}
	}

	function recover_ani(infected) {

		const NODE_EXPAND_TIME = .4;
		const NODE_EXPAND_SCALE = 1.3;
		const NODE_RETRACT_TIME = 2;

		infected.size = options.nodes.size;

		TweenMax.to(infected, NODE_EXPAND_TIME * TIME_SCALE, {
			size: options.nodes.size * NODE_EXPAND_SCALE,
			ease: Power2.easeOut,
			onUpdate: function() {
				nodes.update(infected);
			},
			onComplete: function() {
				TweenMax.to(infected, NODE_RETRACT_TIME * TIME_SCALE, {
					size: options.nodes.size,
					ease: Elastic.easeOut.config(1, 0.3),
					onUpdate: function() {
						nodes.update(infected);
					}
				});
			}
		});
	}
}

function killAttempt() {
	nodesGroup("i").forEach(function(infected) {
		recover(infected);
	});

	function recover(infected) {
		if (Math.random() < infected.death.rate) {
			infected.group = "d";
			nodes.update(infected);
			death_ani(infected);
		}
	}

	function death_ani(infected) {

		const NODE_RETRACT_SCALE = .6;
		const NODE_RETRACT_TIME = 2.5;
		const EDGE_RETRACT_TIME = 1;
		const EDGE_RETRACT_SIZE = 0;
		const EDGE_RETRACT_DELAY = 1;

		infected.size = options.nodes.size;

		var edges_id = network.getConnectedEdges(infected.id);

		edges.get(edges_id).forEach(function(edge) {
			TweenMax.to(edge, EDGE_RETRACT_TIME * TIME_SCALE, {
				width: EDGE_RETRACT_SIZE,
				delay: EDGE_RETRACT_DELAY * TIME_SCALE,
				ease: Power1.easeOut,
				onUpdate: function() {
					edges.update(edge);
				},
				onComplete: function() {
					edges.remove(edge);
				}
			});
		});

		TweenMax.to(infected, NODE_RETRACT_TIME * TIME_SCALE, {
			size: options.nodes.size * NODE_RETRACT_SCALE,
			ease: Power1.easeOut,
			onUpdate: function() {
				nodes.update(infected);
			}
		});
	}
}

function infectAttempt() {
	nodesGroup("i").forEach(function(infected) {
		var neighbors = nodes.get(infected.neighbors);
		neighbors.forEach(function(neighbor) {
			infect(infected, neighbor);
		});
	});

	function infect(infected, target) {
		if (target.group !== "s") return;

		var edge = getEdge(infected, target)[0];
		if (Math.random() < edge.infect.rate) {
			target.group = "t";
			nodes.update(target);
			infect_ani(infected, target);
		}
	}

	function infect_ani(infected, target) {

		const SHAKE_RADIUS = 30;
		const DOT_MOVIMENT_TIME = .6;
		const NODE_EXPAND_TIME = .6;
		const NODE_EXPAND_DELAY = .5;
		const NODE_EXPAND_SCALE = 1.5;
		const NODE_RETRACT_TIME = .4;

		var infected_pos = network.getPositions([infected.id])[infected.id];
		var target_pos = network.getPositions([target.id])[target.id];

		target.size = options.nodes.size;

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

		var draw = function() {
			dot.draw();
		}

		network.on('afterDrawing', draw);

		//Dot moviment animation
		TweenMax.to(dot, DOT_MOVIMENT_TIME * TIME_SCALE, {
			x: target_pos.x,
			y: target_pos.y,
			ease: Power2.easeOut,
			onUpdate: function() {
				network.redraw();
			}
		});

		//Node expand animation
		TweenMax.to(target, NODE_EXPAND_TIME * TIME_SCALE, {
			delay: NODE_EXPAND_DELAY * TIME_SCALE,
			size: options.nodes.size * NODE_EXPAND_SCALE,
			ease: Power2.easeOut,
			onUpdate: function() {
				nodes.update(target);
			},
			onComplete: function() {
				network.off('afterDrawing', draw);
				target.group = "i";
				nodes.update(target);

				//Shake it!
				var hip = SHAKE_RADIUS;
				var angle = Math.atan2(target_pos.y - infected_pos.y, target_pos.x - infected_pos.x);
				network.moveNode(
					target.id,
					target_pos.x + Math.cos(angle) * hip,
					target_pos.y + Math.sin(angle) * hip
				);

				//Node retract animation
				TweenMax.to(target, NODE_RETRACT_TIME * TIME_SCALE, {
					size: options.nodes.size,
					ease: Power3.easeOut,
					onUpdate: function() {
						nodes.update(target);
					}
				});
			}
		});

	}
}

/**
 * Factories
 */

function factoryFullRandom(config) {

	createNetwork({
		nodes: nodesFactory().get(),
		edges: edgesFactory().get()
	});

	function nodesFactory() {

		var nodeConfig = config.node;
		nodes = new vis.DataSet();

		// Get the number of nodes that will be generate
		var quant = Math.round(randomRange(nodeConfig.min, nodeConfig.max));

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
					recover: {
						"rate": randomRange(nodeConfig.recover.rate.min, nodeConfig.recover.rate.max)
					},
					death: {
						"rate": randomRange(nodeConfig.death.rate.min, nodeConfig.death.rate.max)
					}
				});
		}

		return nodes;
	}

	function edgesFactory() {

		var edgesConfig = config.edge;
		edges = new vis.DataSet();

		var quant = Math.round(randomRange(edgesConfig.min, edgesConfig.max));

		//Adds edges
		while (quant > 0) {
			var changed = false;
			nodes.forEach(function(node1) {
				if (quant <= 0) return;
				var pool = nodes.get();
				do {
					var node2 = _.sample(pool, node1); //get a random node
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
						infect: {
							"rate": randomRange(edgesConfig.infect.rate.min, edgesConfig.infect.rate.max)
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

		function getSamples(items) {
			var sample1 = _.sample(items);
			var sample2 = _.sample(_.without(items, sample1));

			return [sample1, sample2];
		}

		return edges;
	}

	function randomRange(min, max) {
		return Math.random() * (max - min) + min;
	}

}