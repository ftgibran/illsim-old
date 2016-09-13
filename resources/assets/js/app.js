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

var network;

var nodes;
var edges;

var options = {
	nodes: {
		borderWidth: 3,
		borderWidthSelected: 3,
		shape: "dot",
		size: NODE_SIZE_MIN
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
	$.getJSON("/data/example.json", createNetwork);
}

function createNetwork(data) {
	network = new vis.Network(container, getData(data), options);
	//$('body').click(infection);
	setInterval(infection, 1000);
}

function getData(data) {

	$.each(data.edges, function(index, edge) {
		edge.label = edge.rate * 100 + "%";
		edge.width = 3 + edge.rate * 10;
	});

	nodes = new vis.DataSet(data.nodes);
	edges = new vis.DataSet(data.edges);

	return {
		nodes: nodes,
		edges: edges
	};
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

	if (target.group !== "i")
		if (Math.random() < edge.rate) {
			edge.rate = 0;
			edges.update(edge);
			infectionAnimation(infected, target);
		}
}

function infectionAnimation(infected, target) {

	setTimeout(ani_infected, ANI_INF_1_DELAY, target);

	var positionsFrom = network.getPositions([infected.id]);
	var domFrom = network.canvasToDOM(positionsFrom[infected.id]);

	var positionsTo = network.getPositions([target.id]);
	var domTo = network.canvasToDOM(positionsTo[target.id]);

	var dot = document.createElement("div");
	container.appendChild(dot);

	dot.className = "dot";

	var width = dot.offsetWidth;
	var height = dot.offsetHeight;

	dot.style["width"] = (width * network.getScale()) + "px";
	dot.style["height"] = (height * network.getScale()) + "px";
	dot.style["left"] = (domFrom.x - width / 2) + "px";
	dot.style["top"] = (domFrom.y - height / 2) + "px";

	TweenMax.to(dot, .6, {
		left: (domTo.x - width / 2) + "px",
		top: (domTo.y - height / 2) + "px",
		//opacity: .5,
		ease: Power2.easeOut,
		onComplete: function() {
			target.group = "i";
			nodes.update(target);
			setTimeout(function() {
				container.removeChild(dot);
				network.moveNode(
					target.id,
					positionsTo[target.id].x + Math.random() * 30 - 15,
					positionsTo[target.id].y + Math.random() * 30 - 15
				);
			}, 500);
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