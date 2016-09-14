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

const NODE_SIZE_MIN = 15;
const NODE_SIZE_MAX = 20;
const FRAME_RATE = 1;
const ANI_INF_1_DELAY = 500;
const ANI_INF_2_DELAY = 400;
const ANI_INF_RISE_RATE = .3;
const ANI_INF_REDUCE_RATE = .6;

/**
 * Initial Configuration
 */
var container = document.getElementById('network');
var canvas;
var context;

var network;
var timeline;

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
		width: 3
	},
	interaction: {
		hover: true,
		selectConnectedEdges: false
	},
	"physics": {
		"minVelocity": 0.05
	},
	groups: {
		i: {
			color: '#D46A6A'
		},
		r: {
			color: '#85BC5E'
		},
		s: {
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
 * Functions
 */

function init() {
	//$.getJSON("/data/example.json", createNetwork);
	$.getJSON("/data/random.json", randomData);
}

function createNetwork(data) {
	network = new vis.Network(container, normalizeData(data), options);

	canvas = document.getElementsByTagName("canvas")[0];
	context = canvas.getContext("2d");

	timeline = new TimelineMax({
		delay: 1,
		repeat: -1,
		onUpdate: infection
	});

	function normalizeData(data) {
		$.each(data.edges, function(index, edge) {
			edge.label = (Math.round(edge.rate * 1000) / 10) + "%";
			edge.width = 3 + edge.rate * 10;
		});

		nodes = new vis.DataSet(data.nodes);
		edges = new vis.DataSet(data.edges);

		return {
			nodes: nodes,
			edges: edges
		};
	}
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

function getEdge(node1, node2) {
	return edges.get({
		filter: function(edge) {
			return (edge.from === node1.id && edge.to === node2.id) || (edge.from === node2.id && edge.to === node1.id);
		}
	});
}

function infects() {
	return nodes.get({
		filter: function(node) {
			return node.group === "i";
		}
	});
}

function infection() {
	infects().forEach(function(infected) {
		var neighbors = getNeighbors(infected);

		neighbors.forEach(function(neighbor) {
			infect(infected, neighbor);
		});
	});
}

function infect(infected, target) {
	var edge = getEdge(infected, target)[0];

	if (target.group === "s")
		if (Math.random() < edge.rate) {
			edge.rate = 0;
			edges.update(edge);
			infectionAnimation(infected, target);
		}
}

function infectionAnimation(infected, target) {

	//setTimeout(ani_infected, ANI_INF_1_DELAY, target);

	var infected_pos = network.getPositions([infected.id])[infected.id];
	var target_pos = network.getPositions([target.id])[target.id];

	var target_size = target.size = 15;

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

	TweenMax.to(dot, .6, {
		x: target_pos.x,
		y: target_pos.y,
		ease: Power2.easeOut,
		onUpdate: function() {
			network.redraw();
		}
	});

	TweenMax.to(target, .6, {
		delay: .5,
		size: target.size * 1.5,
		ease: Power2.easeOut,
		onUpdate: function() {
			nodes.update(target);
		},
		onComplete: function() {
			network.off('afterDrawing', draw);
			target.group = "i";
			nodes.update(target);
			//Shake it!
			network.moveNode(
				target.id,
				target_pos.x + Math.random() * 30 - 15,
				target_pos.y + Math.random() * 30 - 15
			);
			TweenMax.to(target, .4, {
				size: target_size,
				ease: Power3.easeOut,
				onUpdate: function() {
					nodes.update(target);
				}
			});
		}
	});



}

function ani_infected(infected) {
	clearInterval(infected.ani);
	infected.ani = setInterval(ani_infected_1, FRAME_RATE, infected);
}

function ani_infected_1(infected) {
	if (infected.size == undefined) infected.size = NODE_SIZE_MIN;

	if (infected.size >= NODE_SIZE_MAX) {
		clearInterval(infected.ani);
		setTimeout(function() {
			clearInterval(infected.ani);
			infected.ani = setInterval(ani_infected_2, FRAME_RATE, infected, NODE_SIZE_MIN)
		}, ANI_INF_2_DELAY, infected);
	} else {
		infected.size += ANI_INF_RISE_RATE;
		nodes.update(infected);
	}
}

function ani_infected_2(infected) {
	if (infected.size == undefined) infected.size = NODE_SIZE_MAX;

	if (infected.size <= NODE_SIZE_MIN)
		clearInterval(infected.ani);
	else {
		infected.size -= ANI_INF_REDUCE_RATE;
		nodes.update(infected);
	}
}

// Random

function randomData(config) {

	var n = randomNodes(config);
	var e = randomEdges(config);

	createNetwork({
		nodes: n,
		edges: e
	});

}

function randomNodes(config) {

	var nodeConfig = config.node;

	// Get the number of nodes that will be generate
	var quant = Math.round(Math.random() * (nodeConfig.max - nodeConfig.min) + nodeConfig.min);
	var rNodes = [];

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
	addNode(groupLeft, quant - rNodes.length);

	function addNode(group, quant) {
		for (var i = 0; i < quant; i++)
			rNodes.push({
				id: rNodes.length,
				group: group.ref
			});
	}

	nodes = new vis.DataSet(rNodes);
	return rNodes;
}

function randomEdges(config) {

	var edgesConfig = config.edge;
	edges = new vis.DataSet();

	var quant = Math.round(Math.random() * (edgesConfig.max - edgesConfig.min) + edgesConfig.min);

	for (var i = 0; i < quant; i++) {
		var n = 0;
		do {
			var samples = getSamples(nodes.get());
			var edge = getEdge(samples[0], samples[1]);

			//The nodes can't exceed the max edges setted
			if (getNeighbors(samples[0]).length <= config.node.maxEdges && getNeighbors(samples[1]).length <= config.node.maxEdges)
				if (_.isEmpty(edge)) {
					edges.add({
						"from": samples[0].id,
						"to": samples[1].id,
						"rate": Math.random() * (edgesConfig.rate.max - edgesConfig.rate.min) + edgesConfig.rate.min
					});
					break;
				}
		} while (n++ < 10);
	}

	function getSamples(items) {
		var sample1 = _.sample(items);
		var sample2 = _.sample(_.without(items, sample1));

		return [sample1, sample2];
	}

	return edges.get();
}