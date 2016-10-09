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
Vue.component('checkbox', require('./components/Checkbox.vue'));
Vue.component('form-config', require('./components/config/FormConfig.vue'));
Vue.component('form-config-animation', require('./components/config/FormConfigAnimation.vue'));
Vue.component('form-config-simulation', require('./components/config/FormConfigSimulation.vue'));
Vue.component('form-config-factory', require('./components/config/FormConfigFactory.vue'));
Vue.component('form-config-uniform-format', require('./components/config/FormConfigUniformFormat.vue'));
Vue.component('form-config-full-random', require('./components/config/FormConfigFullRandom.vue'));

const app = new Vue({
    el: 'body',

    data() {
        return {
            const: {
                node: {
                    sizeFactor: 15,
                    widthFactor: 8,
                    ratePrecision: 1
                },
                edge: {
                    widthFactor: 10,
                    ratePrecision: 1
                }
            },
            network: null,
            cycle: null,
            nodes: null,
            edges: null,
            config: null
        }
    },

    ready() {
        var $self = this;

        $('select').material_select();

        $self.$refs.config.$watch('data', $self.init);
    },

    methods: {

        init: function(config) {
            var $self = this;

            $self.config = config;
            $self.nodes = new vis.DataSet();
            $self.edges = new vis.DataSet();

            $self.reset();

            $self.create();

            $self.network.once('afterDrawing', function() {
                $self.cycle = setInterval($self.attempt, $self.config.simulation.step);
            });
        },

        create: function() {
            var $self = this;

            var container = document.getElementById('network');
            var data = $self.normalize($self.factory());

            $self.network = new vis.Network(container, data, $self.config.vis);
        },

        reset: function() {
            var $self = this;

            if (_.isNull($self.network)) return;

            clearInterval($self.cycle);
            TweenMax.killAll();
            $self.network.destroy();
        },

        normalize: function(data) {
            var $self = this;

            var $set = {
                node: {
                    size: function(node) {
                        node.base = node.size = $self.config.vis.nodes.size;
                        if ($self.config.simulation.infectBy === 'node' || $self.config.simulation.infectBy === "both")
                            node.base = node.size += node.rate.infect * $self.const.node.sizeFactor;
                    },
                    neighbors: function(node) {
                        var neighbors = [];
                        $.each(data.edges, function(index, edge) {
                            if (edge.from === node.id)
                                neighbors.push(edge.to);
                            else if (edge.to === node.id)
                                neighbors.push(edge.from);
                        });
                        node.neighbors = neighbors;
                    },
                    borderWidth: function(node) {
                        node.borderWidth = node.borderWidthSelected = $self.config.vis.nodes.borderWidth + node.rate.resist * $self.const.node.widthFactor;
                    },
                    label: function(node) {
                        if ($self.config.simulation.infectBy === 'node' || $self.config.simulation.infectBy === "both")
                            node.label = _.round(node.rate.infect * 100, $self.const.node.ratePrecision) + "%";
                    }
                },

                edge: {
                    width: function(edge) {
                        edge.width = $self.config.vis.edges.width;
                        if ($self.config.simulation.infectBy === 'edge' || $self.config.simulation.infectBy === "both")
                            edge.width += edge.rate.infect * $self.const.edge.widthFactor;
                    },
                    label: function(edge) {
                        if ($self.config.simulation.infectBy === 'edge' || $self.config.simulation.infectBy === "both")
                            edge.label = _.round(edge.rate.infect * 100, $self.const.edge.ratePrecision) + "%";
                    }
                }
            };

            _.forEach(data.nodes, function(node) {
                $set.node.size(node);
                $set.node.neighbors(node);
                $set.node.borderWidth(node);
                $set.node.label(node);
            });

            _.forEach(data.edges, function(edge) {
                $set.edge.width(edge);
                $set.edge.label(edge);
            });

            return {
                nodes: new vis.DataSet(data.nodes),
                edges: new vis.DataSet(data.edges)
            };

        },

        getEdge: function(node1, node2) {
            var $self = this;

            return $self.edges.get({
                filter: function(edge) {
                    return (edge.from === node1.id && edge.to === node2.id) || (edge.from === node2.id && edge.to === node1.id);
                }
            });
        },

        nodesGroup: function(group) {
            var $self = this;

            return $self.nodes.get({
                filter: function(node) {
                    return node.group === group;
                }
            });
        },

        setAnimation: function(target, time) {
            var $self = this;

            target.animating = true;
            $self.nodes.update(target);

            TweenMax.delayedCall(time * $self.config.animation.scale, function() {
                target.animating = false;
                $self.nodes.update(target);
            });
        },

        simulationGroup: function(node) {
            var $self = this;
            return eval("$self.config.simulation." + node.group + ";");
        },

        group: function(node, ref) {
            if (node.group === ref)
                return true;

            return false;
        },

        attempt: function() {
            var $self = this;

            $self.nodes.forEach(function(node) {
                $self.infectAttempt(node);
                //$self.recoverAttempt(node);
                //$self.killAttempt(node);
            });
        },

        infectAttempt: function(infected) {
            var $self = this;

            if ($self.config.simulation.infectBy === 'special')
                specialInfect();
            else
                standardInfect();

            function specialInfect() {
                var target = infected;

                if (allowed())
                    infect();

                function allowed() {

                    if (target.animating)
                        return false;

                    if ($self.group(target, "s"))
                        return true;

                    if ($self.group(target, "r") && $self.config.simulation.r.mayBetarget)
                        return true;

                    if ($self.group(target, "v") && $self.config.simulation.v.mayBeInfected)
                        return true;

                    return false;
                }

                function infect() {

                    const K = $self.config.simulation.k;

                    var neighbors = $self.nodes.get(target.neighbors, {
                        filter: function(node) {
                            if (node.animating)
                                return false;

                            if ($self.group(node, "i"))
                                return true;

                            if ($self.group(node, "r") && $self.config.simulation.r.mayInfect)
                                return true;

                            if ($self.group(node, "v") && $self.config.simulation.v.mayInfect)
                                return true;

                            return false;
                        }
                    });

                    if (neighbors.length == 0) return;

                    infected = _.sample(neighbors);

                    var equation = (1 - Math.exp(-K * neighbors.length)) * $self.simulationGroup(infected).base.infect;

                    if (Math.random() > $self.simulationGroup(target).base.resist)
                        if (Math.random() > target.rate.resist)
                            if (Math.random() < equation)
                                infect_ani(target);
                }

            }

            function standardInfect() {

                if (allowed())
                    infect();

                function allowed() {

                    if (infected.animating)
                        return false;

                    if ($self.group(infected, "i"))
                        return true;

                    if ($self.group(infected, "r") && $self.config.simulation.r.mayInfect)
                        return true;

                    if ($self.group(infected, "v") && $self.config.simulation.v.mayInfect)
                        return true;

                    return false;
                }

                function infect() {
                    console.log(infected);
                    var neighbors = $self.nodes.get(infected.neighbors);
                    neighbors.forEach(eachNeighbors);
                }

                function eachNeighbors(target) {

                    if (allowed())
                        switch ($self.config.simulation.infectBy) {
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

                        if ($self.group(target, "s"))
                            return true;

                        if ($self.group(target, "r") && $self.config.simulation.r.mayBeInfected)
                            return true;

                        if ($self.group(target, "v") && $self.config.simulation.v.mayBeInfected)
                            return true;

                        return false;
                    }

                    function infectByNode() {

                        if (Math.random() > $self.simulationGroup(target).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < infected.rate.infect * $self.simulationGroup(infected).base.infect)
                                    infect_ani(target);
                    }

                    function infectByEdge() {

                        var edge = $self.getEdge(infected, target)[0];
                        if (edge == undefined) return;

                        if (Math.random() > $self.simulationGroup(target).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < edge.rate.infect * $self.simulationGroup(infected).base.infect)
                                    infect_ani(target);
                    }

                    function infectByBoth() {

                        var edge = $self.$self.getEdge(infected, target)[0];
                        if (edge == undefined) return;

                        if (Math.random() > $self.simulationGroup(target).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < infected.rate.infect * edge.rate.infect * $self.simulationGroup(infected).base.infect)
                                    infect_ani(target);
                    }

                }

            }

            function infect_ani(target) {

                const DOT_MOVIMENT_TIME = Number($self.config.animation.infect.dotMovimentTime);
                const INFECT_EXPAND_TIME = Number($self.config.animation.infect.expandTime);
                const INFECT_EXPAND_DELAY = Number($self.config.animation.infect.expandDelay);
                const INFECT_EXPAND_SCALE = Number($self.config.animation.infect.expandScale);
                const INFECT_RETRACT_TIME = Number($self.config.animation.infect.retractTime);
                const SHAKE_RADIUS = Number($self.config.animation.infect.shakeRadius);
                const REST_TIME = INFECT_EXPAND_TIME +
                    INFECT_EXPAND_DELAY + INFECT_RETRACT_TIME + Number($self.config.animation.infect.restTime); //1.5

                $self.setAnimation(infected, REST_TIME);
                $self.setAnimation(target, REST_TIME);

                var infectedPos = $self.network.getPositions([infected.id])[infected.id];
                var targetPos = $self.network.getPositions([target.id])[target.id];

                var canvas = document.getElementsByTagName("canvas")[0];
                var context = canvas.getContext("2d");

                var dot = {
                    x: infectedPos.x,
                    y: infectedPos.y,
                    draw: function() {
                        context.beginPath();
                        context.arc(this.x, this.y, 6, 0, 2 * Math.PI, false);
                        context.fillStyle = 'black';
                        context.fill();
                    }
                };

                _.bindAll(dot, 'draw');

                $self.network.on('afterDrawing', dot.draw);

                //Dot moviment animation
                TweenMax.to(dot, DOT_MOVIMENT_TIME * $self.config.animation.scale, {
                    x: targetPos.x,
                    y: targetPos.y,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        $self.network.redraw();
                    }
                });

                //Node expand animation
                TweenMax.to(target, INFECT_EXPAND_TIME * $self.config.animation.scale, {
                    delay: INFECT_EXPAND_DELAY * $self.config.animation.scale,
                    size: target.base * INFECT_EXPAND_SCALE,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        $self.nodes.update(target);
                    },
                    onComplete: function() {
                        $self.network.off('afterDrawing', dot.draw);

                        target.group = "i";
                        $self.nodes.update(target);

                        //Shake it!
                        var hip = SHAKE_RADIUS;
                        var angle = Math.atan2(targetPos.y - infectedPos.y, targetPos.x - infectedPos.x);
                        $self.network.moveNode(
                            target.id,
                            targetPos.x + Math.cos(angle) * hip,
                            targetPos.y + Math.sin(angle) * hip
                        );

                        //Node retract animation
                        TweenMax.to(target, INFECT_RETRACT_TIME * $self.config.animation.scale, {
                            size: target.base,
                            ease: Power3.easeOut,
                            onUpdate: function() {
                                $self.nodes.update(target);
                            }
                        });
                    }
                });

            }

        },

        recoverAttempt: function(target) {
            var $self = this;

            if (allowed())
                recover();

            function allowed() {

                if (target.animating)
                    return false;

                if ($self.group(target, "i"))
                    return true;

                if ($self.group(target, "r") && $self.config.simulation.r.mayGetSusceptible)
                    return true;

                if ($self.group(target, "v") && $self.config.simulation.v.mayGetSusceptible)
                    return true;

                return false;
            }

            function recover() {
                if (Math.random() < target.rate.recover * $self.simulationGroup(target).base.recover)
                    recover_ani();
            }

            function recover_ani() {

                const RECOVER_EXPAND_TIME = Number($self.config.animation.recover.expandTime);
                const RECOVER_EXPAND_SCALE = Number($self.config.animation.recover.expandScale);
                const RECOVER_RETRACT_TIME = Number($self.config.animation.recover.retractTime);
                const REST_TIME = RECOVER_EXPAND_TIME + RECOVER_RETRACT_TIME +
                    Number($self.config.animation.recover.restTime); //2.4

                $self.setAnimation(target, REST_TIME);

                if ($self.group(target, "i"))
                    target.group = "r";
                else
                    target.group = "s";

                TweenMax.to(target, RECOVER_EXPAND_TIME * $self.config.animation.scale, {
                    size: target.base * RECOVER_EXPAND_SCALE,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        $self.nodes.update(target);
                    },
                    onComplete: function() {
                        TweenMax.to(target, RECOVER_RETRACT_TIME * $self.config.animation.scale, {
                            size: target.base,
                            ease: Elastic.easeOut.config(1, 0.3),
                            onUpdate: function() {
                                $self.nodes.update(target);
                            }
                        });
                    }
                });
            }
        },

        killAttempt: function(target) {
            var $self = this;

            if (allowed())
                kill();

            function allowed() {

                if (target.animating)
                    return false;

                if ($self.group(target, "i"))
                    return true;

                if ($self.group(target, "r") && $self.config.simulation.r.mayDie)
                    return true;

                if ($self.group(target, "v") && $self.config.simulation.v.mayDie)
                    return true;

                return false;
            }

            function kill() {
                if (Math.random() < target.rate.death * $self.simulationGroup(target).base.death) {
                    death_ani();
                }
            }

            function death_ani() {

                const DEATH_RETRACT_SCALE = Number($self.config.animation.death.retractScale);
                const DEATH_RETRACT_TIME = Number($self.config.animation.death.retractTime);
                const EDGE_RETRACT_TIME = Number($self.config.animation.death.edgeRetractTime);
                const EDGE_RETRACT_SIZE = Number($self.config.animation.death.edgeRetractSize);
                const EDGE_RETRACT_DELAY = Number($self.config.animation.death.edgeRetractDelay);
                const BIRTH_EXPAND_TIME = Number($self.config.animation.death.birthExpandTime);
                const BIRTH_EXPAND_DELAY = Number($self.config.animation.death.birthExpandDelay);
                const BIRTH_EXPAND_SCALE = Number($self.config.animation.death.birthExpandScale);
                const BIRTH_RETRACT_TIME = Number($self.config.animation.death.birthRetractTime);
                const REST_TIME = DEATH_RETRACT_TIME + BIRTH_EXPAND_TIME +
                    BIRTH_EXPAND_DELAY + BIRTH_RETRACT_TIME + Number($self.config.animation.death.restTime); //6.9

                $self.setAnimation(target, REST_TIME);

                target.group = "d";

                var edges_id = $self.network.getConnectedEdges(target.id);

                //Removing edge animation
                if (!$self.config.simulation.birthWhenDie)
                    $self.edges.get(edges_id, {
                        filter: function(edge) {
                            return !edge.removing;
                        }
                    }).forEach(edge_ani)

                //Node death animation
                TweenMax.to(target, DEATH_RETRACT_TIME * $self.config.animation.scale, {
                    size: $self.config.vis.nodes.size * DEATH_RETRACT_SCALE,
                    ease: Power1.easeOut,
                    onUpdate: function() {
                        $self.nodes.update(target);
                    },
                    onComplete: function() {
                        //Birth animation
                        if ($self.config.simulation.birthWhenDie)
                            birth_ani();
                    }
                });

                function birth_ani() {
                    target.group = "s";
                    TweenMax.to(target, BIRTH_EXPAND_TIME * $self.config.animation.scale, {
                        size: target.base * BIRTH_EXPAND_SCALE,
                        ease: Power2.easeOut,
                        delay: BIRTH_EXPAND_DELAY * $self.config.animation.scale,
                        onUpdate: function() {
                            $self.nodes.update(target);
                        },
                        onComplete: function() {
                            TweenMax.to(target, BIRTH_RETRACT_TIME * $self.config.animation.scale, {
                                size: target.base,
                                ease: Elastic.easeOut.$self.config(1, 0.3),
                                onUpdate: function() {
                                    $self.nodes.update(target);
                                }
                            });
                        }
                    });
                }

                function edge_ani(edge) {

                    edge.removing = true;
                    $self.edges.update(edge);

                    TweenMax.to(edge, EDGE_RETRACT_TIME * $self.config.animation.scale, {
                        width: EDGE_RETRACT_SIZE,
                        delay: EDGE_RETRACT_DELAY * $self.config.animation.scale,
                        ease: Power1.easeOut,
                        onUpdate: function() {
                            $self.edges.update(edge);
                        },
                        onComplete: function() {
                            var node1 = $self.nodes.get(edge.from);
                            var node2 = $self.nodes.get(edge.to);

                            node1.neighbors = _.without(node1.neighbors, node2);
                            node2.neighbors = _.without(node2.neighbors, node1);

                            $self.nodes.update(node1, node2);
                            $self.edges.remove(edge);
                        }
                    });
                }
            }

        },

        factory: function() {
            var $self = this;

            switch ($self.config.factory.method) {
                case 'uniform-format':
                    return $self.factoryUniformFormat();

                case 'full-random':
                    return $self.factoryFullRandom();
            }

            return false;
        },

        factoryUniformFormat: function() {
            var $self = this;

            $self.config.vis.physics.forceAtlas2Based.centralGravity = 0.001;

            return {
                nodes: nodesFactory(),
                edges: edgesFactory()
            };

            function nodesFactory() {
                var nodeConfig = $self.config.factory.node;

                for (var i = 0; i < Math.pow($self.config.factory.level, 2); i++)
                    $self.nodes.add({
                        id: i,
                        group: "s",
                        rate: nodeConfig.rate
                    });

                //Scan each node group and apply the quantity
                _.forEach(nodeConfig.groups, function(group) {
                    for (var i = 0; i < group.quant; i++) {
                        var node = _.sample($self.nodes.get());
                        node.group = group.ref;
                        $self.nodes.update(node);
                    }
                });

                return $self.nodes.get();
            }

            function edgesFactory() {
                var edgeConfig = $self.config.factory.edge;
                var row = $self.config.factory.level;
                var line = $self.config.factory.level - 1;

                for (var i = 0; i < row; i++)
                    for (var j = 0; j < line; j++) {
                        var node1 = $self.nodes.get(i * row + j);
                        var node2 = $self.nodes.get(i * row + (j + 1));

                        $self.edges.add({
                            from: node1.id,
                            to: node2.id,
                            rate: edgeConfig.rate
                        });
                    }

                for (var i = 0; i < row; i++)
                    for (var j = 0; j < line; j++) {
                        var node1 = $self.nodes.get(j * row + i);
                        var node2 = $self.nodes.get((j + 1) * row + i);

                        $self.edges.add({
                            from: node1.id,
                            to: node2.id,
                            rate: edgeConfig.rate
                        });
                    }

                return $self.edges.get();
            }
        },

        factoryFullRandom: function() {
            var $self = this;

            return {
                nodes: nodesFactory(),
                edges: edgesFactory()
            };

            function nodesFactory() {

                var nodeConfig = $self.config.factory.node;
                var rate = nodeConfig.rate;

                // Get the number of nodes that will be generate
                var quant = _.random(nodeConfig.min, nodeConfig.max);

                //Get a group left to avoid undefined
                var groupLeft = {
                    "ref": "s"
                };

                //Scan each node group and apply the quantity
                _.forEach(nodeConfig.groups, function(group) {
                    if (group.quant === undefined) {
                        groupLeft = group;
                        return;
                    }
                    if (group.percent)
                        addNode(group, group.quant / 100 * quant);
                    else
                        addNode(group, group.quant);

                });

                //Add what is left
                addNode(groupLeft, quant - $self.nodes.length);

                function addNode(group, quant) {
                    for (var i = 0; i < quant; i++)
                        $self.nodes.add({
                            id: $self.nodes.length,
                            group: group.ref,
                            rate: {
                                infect: _.random(rate.infect.min, rate.infect.max, true),
                                resist: _.random(rate.resist.min, rate.resist.max, true),
                                recover: _.random(rate.recover.min, rate.recover.max, true),
                                death: _.random(rate.death.min, rate.death.max, true)
                            }
                        });
                }

                return $self.nodes.get();
            }

            function edgesFactory() {

                var edgeConfig = $self.config.factory.edge;
                var rate = edgeConfig.rate;

                var quant = _.random(edgeConfig.min, edgeConfig.max);

                //Adds edges
                while (quant > 0) {
                    var changed = false;
                    $self.nodes.forEach(function(node1) {
                        if (quant <= 0) return;
                        var pool = $self.nodes.get();
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
                    var edge = $self.getEdge(node1, node2);

                    //The nodes can't exceed the max edges setted
                    if (neighbors1.length < $self.config.factory.node.maxEdges && neighbors2.length < $self.config.factory.node.maxEdges)
                        if (_.isEmpty(edge)) {
                            $self.edges.add({
                                from: node1.id,
                                to: node2.id,
                                rate: {
                                    infect: _.random(rate.infect.min, rate.infect.max, true)
                                }
                            });
                            return true;
                        }
                    return false;
                }

                function getNeighbors(node) {
                    var neighbors = [];
                    $self.edges.forEach(function(edge) {
                        if (edge.from === node.id)
                            neighbors.push($self.nodes.get(edge.to));
                        else if (edge.to === node.id)
                            neighbors.push($self.nodes.get(edge.from));
                    });
                    return neighbors;
                }

                return $self.edges.get();
            }

        }

    }

});
