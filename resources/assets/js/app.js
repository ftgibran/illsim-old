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

Vue.component('ui-loader', require('./components/Loader.vue'));
Vue.component('ui-nav', require('./components/Nav.vue'));
Vue.component('ui-sidenav', require('./components/SideNav.vue'));
Vue.component('ui-slider', require('./components/Slider.vue'));
Vue.component('ui-checkbox', require('./components/Checkbox.vue'));
Vue.component('ui-stats', require('./components/Stats.vue'));
Vue.component('form-config', require('./components/config/FormConfig.vue'));
Vue.component('form-config-animation', require('./components/config/FormConfigAnimation.vue'));
Vue.component('form-config-simulation', require('./components/config/FormConfigSimulation.vue'));
Vue.component('form-config-factory', require('./components/config/FormConfigFactory.vue'));
Vue.component('form-config-uniform-format', require('./components/config/FormConfigUniformFormat.vue'));
Vue.component('form-config-full-random', require('./components/config/FormConfigFullRandom.vue'));

const Data = {
    const: {
        node: {
            sizeFactor: 15,
            widthFactor: 8,
            ratePrecision: 1
        },
        edge: {
            widthFactor: 10,
            ratePrecision: 1
        },
        dot: {
            size: 6
        },
        status: {
            INFECTED: "i",
            RECOVERED: "r",
            SUSCEPTIBLE: "s",
            VACCINATED: "v",
            DEATH: "d"
        }
    },
    context: null,
    network: null,
    loop: null,
    nodes: null,
    edges: null,
    config: null
}

const app = new Vue({
    el: 'body',

    ready() {
        var $self = this;

        $('select').material_select();
        $self.$refs.loader.hide();

        this.$refs.config.$on('submit', this.init);
    },

    data() {
        return {
            susceptible: 0,
            infected: 0,
            recovered: 0,
            vaccinated: 0,
            death: 0,
            population: 0,
        }
    },

    events: {
        statusChange: function(target, group) {
            var $self = this;

            if ($self.isGroup(target, Data.const.status.SUSCEPTIBLE))
                $self.susceptible--;

            if ($self.isGroup(target, Data.const.status.DEATH))
                if (group === Data.const.status.SUSCEPTIBLE)
                    $self.population++;

            switch (group) {
                case Data.const.status.SUSCEPTIBLE:
                    $self.susceptible++;
                    break;
                case Data.const.status.INFECTED:
                    $self.infected++;
                    break;
                case Data.const.status.RECOVERED:
                    $self.recovered++;
                    break;
                case Data.const.status.VACCINATED:
                    $self.vaccinated++;
                    break;
                case Data.const.status.DEATH:
                    $self.death++;
                    $self.population--;
                    break;
            }

            target.group = group;
            Data.nodes.update(target);
        }
    },

    methods: {

        /**
         * Initialization
         */

        init: function(config) {
            var $self = this;

            //Resets Data.network
            $self.reset();

            $self.$refs.sidenav.hide();
            $self.$refs.loader.show();

            _.delay(function() {
                Data.config = config;

                //Creates Data.network
                $self.create();

                //Set attempts interval loop
                Data.network.once('afterDrawing', function() {
                    $self.$refs.loader.hide();
                    Data.loop = setInterval($self.attempt, Data.config.simulation.step);
                });
            }, $self.$refs.sidenav.time * 1000);

        },

        create: function() {
            var $self = this;

            var container = document.getElementById('network');
            var data = $self.normalize($self.factory());

            $self.setStats();

            Data.network = new vis.Network(container, data, Data.config.vis);

            var canvas = document.getElementsByTagName("canvas")[0];
            Data.context = canvas.getContext("2d");
        },

        reset: function() {
            if (_.isNull(Data.network)) return;

            clearInterval(Data.loop);
            TweenMax.killAll();
            Data.network.destroy();

            Data.nodes = null;
            Data.edges = null;
            Data.config = null;
            Data.context = null;

            this.susceptible = 0;
            this.infected = 0;
            this.recovered = 0;
            this.vaccinated = 0;
            this.death = 0;
            this.population = 0;
        },

        normalize: function(data) {
            var $self = this;

            var $set = {
                node: {
                    size: function(node) {
                        node.base = node.size = Data.config.vis.nodes.size;
                        if (Data.config.simulation.infectBy === 'node' || Data.config.simulation.infectBy === "both")
                            node.base = node.size += node.rate.infect * Data.const.node.sizeFactor;
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
                        node.borderWidth = node.borderWidthSelected = Data.config.vis.nodes.borderWidth + node.rate.resist * Data.const.node.widthFactor;
                    },
                    label: function(node) {
                        if (Data.config.simulation.infectBy === 'node' || Data.config.simulation.infectBy === "both")
                            node.label = _.round(node.rate.infect * 100, Data.const.node.ratePrecision) + "%";
                    }
                },

                edge: {
                    width: function(edge) {
                        edge.width = Data.config.vis.edges.width;
                        if (Data.config.simulation.infectBy === 'edge' || Data.config.simulation.infectBy === "both")
                            edge.width += edge.rate.infect * Data.const.edge.widthFactor;
                    },
                    label: function(edge) {
                        if (Data.config.simulation.infectBy === 'edge' || Data.config.simulation.infectBy === "both")
                            edge.label = _.round(edge.rate.infect * 100, Data.const.edge.ratePrecision) + "%";
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

            Data.nodes = new vis.DataSet(data.nodes);
            Data.edges = new vis.DataSet(data.edges);

            return {
                nodes: Data.nodes,
                edges: Data.edges
            };

        },

        /**
         * Utils
         */

        rest: function(target, time) {
            target.animating = true;
            Data.nodes.update(target);

            TweenMax.delayedCall(time * Data.config.animation.scale, function() {
                target.animating = false;
                Data.nodes.update(target);
            });
        },

        isGroup: function(node, ref) {
            if (node.group === ref)
                return true;

            return false;
        },

        groupConfig: function(ref) {

            return eval("Data.config.simulation." + ref + ";");
        },

        filterEdgesByNodes: function(node1, node2) {
            return Data.edges.get({
                filter: function(edge) {
                    return (edge.from === node1.id && edge.to === node2.id) || (edge.from === node2.id && edge.to === node1.id);
                }
            });
        },

        filterNodesByGroup: function(group) {
            return Data.nodes.get({
                filter: function(node) {
                    return node.group === group;
                }
            });
        },

        setStats: function() {
            var $self = this;

            $self.population = Data.nodes.length;

            _.forEach(Data.nodes.get(), function(node) {
                switch (node.group) {
                    case Data.const.status.SUSCEPTIBLE:
                        $self.susceptible++;
                        break;

                    case Data.const.status.INFECTED:
                        $self.infected++;
                        break;

                    case Data.const.status.RECOVERED:
                        $self.recovered++;
                        break;

                    case Data.const.status.VACCINATED:
                        $self.vaccinated++;
                        break;

                    case Data.const.status.DEATH:
                        $self.death++;
                        break;

                }
            });
        },

        /**
         * Attempts
         */

        attempt: function() {
            var $self = this;

            Data.nodes.forEach(function(node) {
                $self.infectAttempt(node);
                $self.recoverAttempt(node);
                $self.killAttempt(node);
            });
        },

        infectAttempt: function(infected) {
            var $self = this;

            if (Data.config.simulation.infectBy === 'special')
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

                    if ($self.isGroup(target, Data.const.status.SUSCEPTIBLE))
                        return true;

                    if ($self.isGroup(target, Data.const.status.RECOVERED) && Data.config.simulation.r.mayBetarget)
                        return true;

                    if ($self.isGroup(target, Data.const.status.VACCINATED) && Data.config.simulation.v.mayBeInfected)
                        return true;

                    return false;
                }

                function infect() {

                    const K = Data.config.simulation.k;

                    var neighbors = Data.nodes.get(target.neighbors, {
                        filter: function(node) {
                            if (node.animating)
                                return false;

                            if ($self.isGroup(node, Data.const.status.INFECTED))
                                return true;

                            if ($self.isGroup(node, Data.const.status.RECOVERED) && Data.config.simulation.r.mayInfect)
                                return true;

                            if ($self.isGroup(node, Data.const.status.VACCINATED) && Data.config.simulation.v.mayInfect)
                                return true;

                            return false;
                        }
                    });

                    if (neighbors.length == 0) return;

                    infected = _.sample(neighbors);

                    var equation = (1 - Math.exp(-K * neighbors.length)) * $self.groupConfig(infected.group).base.infect;

                    if (Math.random() > $self.groupConfig(target.group).base.resist)
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

                    if ($self.isGroup(infected, Data.const.status.INFECTED))
                        return true;

                    if ($self.isGroup(infected, Data.const.status.RECOVERED) && Data.config.simulation.r.mayInfect)
                        return true;

                    if ($self.isGroup(infected, Data.const.status.VACCINATED) && Data.config.simulation.v.mayInfect)
                        return true;

                    return false;
                }

                function infect() {
                    var neighbors = Data.nodes.get(infected.neighbors);
                    neighbors.forEach(eachNeighbors);
                }

                function eachNeighbors(target) {

                    if (allowed())
                        switch (Data.config.simulation.infectBy) {
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

                        if ($self.isGroup(target, Data.const.status.SUSCEPTIBLE))
                            return true;

                        if ($self.isGroup(target, Data.const.status.RECOVERED) && Data.config.simulation.r.mayBeInfected)
                            return true;

                        if ($self.isGroup(target, Data.const.status.VACCINATED) && Data.config.simulation.v.mayBeInfected)
                            return true;

                        return false;
                    }

                    function infectByNode() {

                        if (Math.random() > $self.groupConfig(target.group).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < infected.rate.infect * $self.groupConfig(infected.group).base.infect)
                                    infect_ani(target);
                    }

                    function infectByEdge() {

                        var edge = $self.filterEdgesByNodes(infected, target)[0];
                        if (edge == undefined) return;

                        if (Math.random() > $self.groupConfig(target.group).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < edge.rate.infect * $self.groupConfig(infected.group).base.infect)
                                    infect_ani(target);
                    }

                    function infectByBoth() {

                        var edge = $self.filterEdgesByNodes(infected, target)[0];
                        if (edge == undefined) return;

                        if (Math.random() > $self.groupConfig(target.group).base.resist)
                            if (Math.random() > target.rate.resist)
                                if (Math.random() < infected.rate.infect * edge.rate.infect * $self.groupConfig(infected.group).base.infect)
                                    infect_ani(target);
                    }

                }

            }

            function infect_ani(target) {

                const DOT_MOVIMENT_TIME = Number(Data.config.animation.infect.dotMovimentTime);
                const INFECT_EXPAND_TIME = Number(Data.config.animation.infect.expandTime);
                const INFECT_EXPAND_DELAY = Number(Data.config.animation.infect.expandDelay);
                const INFECT_EXPAND_SCALE = Number(Data.config.animation.infect.expandScale);
                const INFECT_RETRACT_TIME = Number(Data.config.animation.infect.retractTime);
                const SHAKE_RADIUS = Number(Data.config.animation.infect.shakeRadius);
                const REST_TIME = INFECT_EXPAND_TIME +
                    INFECT_EXPAND_DELAY + INFECT_RETRACT_TIME + Number(Data.config.animation.infect.restTime); //1.5

                $self.rest(infected, REST_TIME);
                $self.rest(target, REST_TIME);

                var infectedPos = Data.network.getPositions([infected.id])[infected.id];
                var targetPos = Data.network.getPositions([target.id])[target.id];

                var dot = {
                    x: infectedPos.x,
                    y: infectedPos.y,
                    size: Data.const.dot.size,
                    draw: function() {
                        Data.context.beginPath();
                        Data.context.arc(this.x, this.y, this.size, 0, 2 * Math.PI, false);
                        Data.context.fillStyle = 'black';
                        Data.context.fill();
                    }
                };

                _.bindAll(dot, 'draw');

                Data.network.on('afterDrawing', dot.draw);

                //Dot moviment animation
                TweenMax.to(dot, DOT_MOVIMENT_TIME * Data.config.animation.scale, {
                    x: targetPos.x,
                    y: targetPos.y,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        Data.network.redraw();
                    }
                });

                //Node expand animation
                TweenMax.to(target, INFECT_EXPAND_TIME * Data.config.animation.scale, {
                    delay: INFECT_EXPAND_DELAY * Data.config.animation.scale,
                    size: target.base * INFECT_EXPAND_SCALE,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        Data.nodes.update(target);
                    },
                    onComplete: function() {
                        Data.network.off('afterDrawing', dot.draw);

                        $self.$emit('statusChange', target, Data.const.status.INFECTED);

                        //Shake it!
                        var hip = SHAKE_RADIUS;
                        var angle = Math.atan2(targetPos.y - infectedPos.y, targetPos.x - infectedPos.x);
                        Data.network.moveNode(
                            target.id,
                            targetPos.x + Math.cos(angle) * hip,
                            targetPos.y + Math.sin(angle) * hip
                        );

                        //Node retract animation
                        TweenMax.to(target, INFECT_RETRACT_TIME * Data.config.animation.scale, {
                            size: target.base,
                            ease: Power3.easeOut,
                            onUpdate: function() {
                                Data.nodes.update(target);
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

                if ($self.isGroup(target, Data.const.status.INFECTED))
                    return true;

                if ($self.isGroup(target, Data.const.status.RECOVERED) && Data.config.simulation.r.mayGetSusceptible)
                    return true;

                if ($self.isGroup(target, Data.const.status.VACCINATED) && Data.config.simulation.v.mayGetSusceptible)
                    return true;

                return false;
            }

            function recover() {
                if (Math.random() < target.rate.recover * $self.groupConfig(target.group).base.recover)
                    recover_ani();
            }

            function recover_ani() {

                const RECOVER_EXPAND_TIME = Number(Data.config.animation.recover.expandTime);
                const RECOVER_EXPAND_SCALE = Number(Data.config.animation.recover.expandScale);
                const RECOVER_RETRACT_TIME = Number(Data.config.animation.recover.retractTime);
                const REST_TIME = RECOVER_EXPAND_TIME + RECOVER_RETRACT_TIME +
                    Number(Data.config.animation.recover.restTime); //2.4

                $self.rest(target, REST_TIME);

                TweenMax.to(target, RECOVER_EXPAND_TIME * Data.config.animation.scale, {
                    size: target.base * RECOVER_EXPAND_SCALE,
                    ease: Power2.easeOut,
                    onUpdate: function() {
                        Data.nodes.update(target);
                    },
                    onComplete: function() {

                        if ($self.isGroup(target, Data.const.status.INFECTED))
                            $self.$emit('statusChange', target, Data.const.status.RECOVERED);
                        else
                            $self.$emit('statusChange', target, Data.const.status.SUSCEPTIBLE);

                        TweenMax.to(target, RECOVER_RETRACT_TIME * Data.config.animation.scale, {
                            size: target.base,
                            ease: Elastic.easeOut.config(1, 0.3),
                            onUpdate: function() {
                                Data.nodes.update(target);
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

                if ($self.isGroup(target, Data.const.status.INFECTED))
                    return true;

                if ($self.isGroup(target, Data.const.status.RECOVERED) && Data.config.simulation.r.mayDie)
                    return true;

                if ($self.isGroup(target, Data.const.status.VACCINATED) && Data.config.simulation.v.mayDie)
                    return true;

                return false;
            }

            function kill() {
                if (Math.random() < target.rate.death * $self.groupConfig(target.group).base.death) {
                    death_ani();
                }
            }

            function death_ani() {

                const DEATH_RETRACT_SCALE = Number(Data.config.animation.death.retractScale);
                const DEATH_RETRACT_TIME = Number(Data.config.animation.death.retractTime);
                const EDGE_RETRACT_TIME = Number(Data.config.animation.death.edgeRetractTime);
                const EDGE_RETRACT_SIZE = Number(Data.config.animation.death.edgeRetractSize);
                const EDGE_RETRACT_DELAY = Number(Data.config.animation.death.edgeRetractDelay);
                const BIRTH_EXPAND_TIME = Number(Data.config.animation.death.birthExpandTime);
                const BIRTH_EXPAND_DELAY = Number(Data.config.animation.death.birthExpandDelay);
                const BIRTH_EXPAND_SCALE = Number(Data.config.animation.death.birthExpandScale);
                const BIRTH_RETRACT_TIME = Number(Data.config.animation.death.birthRetractTime);
                const REST_TIME = DEATH_RETRACT_TIME + BIRTH_EXPAND_TIME +
                    BIRTH_EXPAND_DELAY + BIRTH_RETRACT_TIME + Number(Data.config.animation.death.restTime); //6.9

                $self.rest(target, REST_TIME);

                $self.$emit('statusChange', target, Data.const.status.DEATH);

                var edges_id = Data.network.getConnectedEdges(target.id);

                //Removing edge animation
                if (!Data.config.simulation.birthWhenDie)
                    Data.edges.get(edges_id, {
                        filter: function(edge) {
                            return !edge.removing;
                        }
                    }).forEach(edge_ani)

                //Node death animation
                TweenMax.to(target, DEATH_RETRACT_TIME * Data.config.animation.scale, {
                    size: Data.config.vis.nodes.size * DEATH_RETRACT_SCALE,
                    ease: Power1.easeOut,
                    onUpdate: function() {
                        Data.nodes.update(target);
                    },
                    onComplete: function() {
                        //Birth animation
                        if (Data.config.simulation.birthWhenDie)
                            birth_ani();
                    }
                });

                function birth_ani() {
                    TweenMax.to(target, BIRTH_EXPAND_TIME * Data.config.animation.scale, {
                        size: target.base * BIRTH_EXPAND_SCALE,
                        ease: Power2.easeOut,
                        delay: BIRTH_EXPAND_DELAY * Data.config.animation.scale,
                        onUpdate: function() {
                            Data.nodes.update(target);
                        },
                        onComplete: function() {

                            $self.$emit('statusChange', target, Data.const.status.SUSCEPTIBLE);

                            TweenMax.to(target, BIRTH_RETRACT_TIME * Data.config.animation.scale, {
                                size: target.base,
                                ease: Elastic.easeOut.config(1, 0.3),
                                onUpdate: function() {
                                    Data.nodes.update(target);
                                }
                            });
                        }
                    });
                }

                function edge_ani(edge) {

                    edge.removing = true;
                    Data.edges.update(edge);

                    TweenMax.to(edge, EDGE_RETRACT_TIME * Data.config.animation.scale, {
                        width: EDGE_RETRACT_SIZE,
                        delay: EDGE_RETRACT_DELAY * Data.config.animation.scale,
                        ease: Power1.easeOut,
                        onUpdate: function() {
                            Data.edges.update(edge);
                        },
                        onComplete: function() {
                            var node1 = Data.nodes.get(edge.from);
                            var node2 = Data.nodes.get(edge.to);

                            node1.neighbors = _.without(node1.neighbors, node2);
                            node2.neighbors = _.without(node2.neighbors, node1);

                            Data.nodes.update(node1, node2);
                            Data.edges.remove(edge);
                        }
                    });
                }
            }

        },

        /**
         * Factories
         */

        factory: function() {
            var $self = this;

            Data.nodes = new vis.DataSet();
            Data.edges = new vis.DataSet();

            switch (Data.config.factory.method) {
                case 'uniform-format':
                    return $self.factoryUniformFormat();

                case 'full-random':
                    return $self.factoryFullRandom();
            }

            return false;
        },

        factoryUniformFormat: function() {
            var $self = this;

            Data.config.vis.physics.forceAtlas2Based.centralGravity = 0.001;

            return {
                nodes: nodesFactory(),
                edges: edgesFactory()
            };

            function nodesFactory() {
                var nodeConfig = Data.config.factory.node;
                var quant = Math.pow(Data.config.factory.level, 2);

                for (var i = 0; i < quant; i++)
                    Data.nodes.add({
                        id: i,
                        group: Data.const.status.SUSCEPTIBLE,
                        rate: nodeConfig.rate
                    });

                //Scan each node group and apply the quantity
                _.forEach(nodeConfig.groups, function(group) {
                    for (var i = 0; i < group.quant; i++) {
                        var node = _.sample(Data.nodes.get());
                        node.group = group.ref;
                        Data.nodes.update(node);
                    }
                });

                return Data.nodes.get();
            }

            function edgesFactory() {
                var edgeConfig = Data.config.factory.edge;
                var row = Data.config.factory.level;
                var line = Data.config.factory.level - 1;

                for (var i = 0; i < row; i++)
                    for (var j = 0; j < line; j++) {
                        var node1 = Data.nodes.get(i * row + j);
                        var node2 = Data.nodes.get(i * row + (j + 1));

                        Data.edges.add({
                            from: node1.id,
                            to: node2.id,
                            rate: edgeConfig.rate
                        });
                    }

                for (var i = 0; i < row; i++)
                    for (var j = 0; j < line; j++) {
                        var node1 = Data.nodes.get(j * row + i);
                        var node2 = Data.nodes.get((j + 1) * row + i);

                        Data.edges.add({
                            from: node1.id,
                            to: node2.id,
                            rate: edgeConfig.rate
                        });
                    }

                return Data.edges.get();
            }
        },

        factoryFullRandom: function() {
            var $self = this;

            return {
                nodes: nodesFactory(),
                edges: edgesFactory()
            };

            function nodesFactory() {

                var nodeConfig = Data.config.factory.node;
                var rate = nodeConfig.rate;

                // Get the number of nodes that will be generate
                var quant = _.random(nodeConfig.min, nodeConfig.max);

                //Get a group left to avoid undefined
                var groupLeft = {
                    "ref": Data.const.status.SUSCEPTIBLE
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
                addNode(groupLeft, quant - Data.nodes.length);

                function addNode(group, quant) {
                    for (var i = 0; i < quant; i++)
                        Data.nodes.add({
                            id: Data.nodes.length,
                            group: group.ref,
                            rate: {
                                infect: _.random(rate.infect.min, rate.infect.max, true),
                                resist: _.random(rate.resist.min, rate.resist.max, true),
                                recover: _.random(rate.recover.min, rate.recover.max, true),
                                death: _.random(rate.death.min, rate.death.max, true)
                            }
                        });
                }

                return Data.nodes.get();
            }

            function edgesFactory() {

                var edgeConfig = Data.config.factory.edge;
                var rate = edgeConfig.rate;

                var quant = _.random(edgeConfig.min, edgeConfig.max);

                //Adds edges
                while (quant > 0) {
                    var changed = false;
                    Data.nodes.forEach(function(node1) {
                        if (quant <= 0) return;
                        var pool = Data.nodes.get();
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
                    var edge = $self.filterEdgesByNodes(node1, node2);

                    //The nodes can't exceed the max edges setted
                    if (neighbors1.length < Data.config.factory.node.maxEdges && neighbors2.length < Data.config.factory.node.maxEdges)
                        if (_.isEmpty(edge)) {
                            Data.edges.add({
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
                    Data.edges.forEach(function(edge) {
                        if (edge.from === node.id)
                            neighbors.push(Data.nodes.get(edge.to));
                        else if (edge.to === node.id)
                            neighbors.push(Data.nodes.get(edge.from));
                    });
                    return neighbors;
                }

                return Data.edges.get();
            }

        }

    }

});
