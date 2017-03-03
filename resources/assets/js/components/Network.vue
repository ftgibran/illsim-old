<template>

    <div class="content">
        <ui-loader :lazy="true">
            <ui-stats></ui-stats>
        </ui-loader>

        <div id="network"></div>
    </div>

    <analytics v-ref:analytics></analytics>

</template>

<style lang="sass" rel="stylesheet/scss" scoped>

    #network {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .content {
        height: 100%;
    }

</style>

<script>
    import Data from '../data';

    export default {

        data() {
            return {
                state: null,
                susceptible: 0,
                infected: 0,
                recovered: 0,
                vaccinated: 0,
                death: 0,
                shots: 0
            }
        },

        computed: {
            population() {
                return this.susceptible + this.infected + this.recovered + this.vaccinated;
            }
        },

        events: {

            play: function (rate) {
                this.state = 'playing';
                Data.step = setInterval(this.step, rate);
            },

            stop: function () {
                this.state = 'paused';
                clearInterval(Data.step);
            },

            reset: function () {
                this.state = 'initializing';
            },

            statusChange: function (target, group) {
                var $self = this;

                switch (target.group) {
                    case Data.const.status.SUSCEPTIBLE:
                        $self.susceptible--;
                        break;
                    case Data.const.status.INFECTED:
                        $self.infected--;
                        break;
                    case Data.const.status.RECOVERED:
                        $self.recovered--;
                        break;
                    case Data.const.status.VACCINATED:
                        $self.vaccinated--;
                        break;
                    case Data.const.status.DEATH:
                        $self.death--;
                        break;
                }

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
                        break;
                }

                target.group = group;
                Data.nodes.update(target);
            }
        },

        methods: {

            init: function (config) {
                //Resets Data.network
                this.reset();

                //Creates Data.network
                _.delay(() => {
                    this.create(config)
                }, 1000);
            },

            create: function (config) {
                Data.config = config;

                //Analytics
                this.$refs.analytics.init(Data.config.analytics);

                var container = document.getElementById('network');
                var data = this.normalize(this.factory());

                this.setStats();

                if (this.mode('visual')) {
                    Data.network = new vis.Network(container, data, Data.config.vis);

                    var canvas = document.getElementsByTagName("canvas")[0];
                    Data.context = canvas.getContext("2d");

                    //Set attempts interval loop
                    Data.network.once('afterDrawing', () => {
                        this.load();
                        this.play();
                    });
                }

                if (this.mode('scientific')) {
                    this.load();
                    this.play(0);
                    this.$refs.analytics.open();
                }
            },

            play: function (rate) {
                if (this.state != 'paused' && this.state != 'initializing')
                    return;

                if (Data.step != null)
                    Data.step = null;

                if (rate == null)
                    rate = Data.config.simulation.step;

                this.$emit('play', rate);
            },

            stop: function () {
                if (this.state != 'playing')
                    return;

                this.$emit('stop');
            },

            load: function () {
                this.$emit('load');
                this.$refs.analytics.$emit('load');
            },

            reset: function () {
                if (!_.isNull(Data.network))
                    Data.network.destroy();

                clearInterval(Data.step);
                TweenMax.killAll();

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
                this.shots = 0;

                this.$emit('reset');
                this.$refs.analytics.$emit('reset');
                this.$root.$emit('reset');
            },

            normalize: function (data) {
                var $self = this;

                var $set = {
                    node: {
                        size: function (node) {
                            node.base = node.size = Data.config.vis.nodes.size;
                            if (Data.config.simulation.infectBy === 'node' || Data.config.simulation.infectBy === "both")
                                node.base = node.size += node.rate.infect * Data.const.node.sizeFactor;
                        },
                        borderWidth: function (node) {
                            node.borderWidth = node.borderWidthSelected = Data.config.vis.nodes.borderWidth + node.rate.resist * Data.const.node.widthFactor;
                        },
                        label: function (node) {
                            if (Data.config.simulation.infectBy === 'node' || Data.config.simulation.infectBy === "both")
                                node.label = _.round(node.rate.infect * 100, Data.const.node.ratePrecision) + "%";
                        }
                    },

                    edge: {
                        width: function (edge) {
                            edge.width = Data.config.vis.edges.width;
                            if (Data.config.simulation.infectBy === 'edge' || Data.config.simulation.infectBy === "both")
                                edge.width += edge.rate.infect * Data.const.edge.widthFactor;
                        },
                        label: function (edge) {
                            if (Data.config.simulation.infectBy === 'edge' || Data.config.simulation.infectBy === "both")
                                edge.label = _.round(edge.rate.infect * 100, Data.const.edge.ratePrecision) + "%";
                        }
                    }
                };

                _.forEach(data.nodes, function (node) {
                    $set.node.size(node);
                    $set.node.borderWidth(node);
                    $set.node.label(node);
                });

                _.forEach(data.edges, function (edge) {
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

            setStats: function () {
                var $self = this;

                $self.population = Data.nodes.length;

                _.forEach(Data.nodes.get(), function (node) {
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
             * Utils
             */

            rest: function (target, time) {
                target.animating = true;
                Data.nodes.update(target);

                TweenMax.delayedCall(time * Data.config.animation.scale, function () {
                    target.animating = false;
                    Data.nodes.update(target);
                });
            },

            allowed: function (target, action) {
                if (target.animating)
                    return false;

                if (Data.config.simulation[target.group][action])
                    return true;

                return false;
            },

            isGroup: function (node, ref) {
                if (node.group === ref)
                    return true;

                return false;
            },

            mode: function (value) {
                if (Data.config.simulation.mode == value)
                    return true;

                return false;
            },

            filterEdgesByNodes: function (node1, node2) {
                return Data.edges.get({
                    filter: function (edge) {
                        return (edge.from === node1.id && edge.to === node2.id) || (edge.from === node2.id && edge.to === node1.id);
                    }
                });
            },

            filterNodesByGroup: function (group) {
                return Data.nodes.get({
                    filter: function (node) {
                        return node.group === group;
                    }
                });
            },

            /**
             * Attempts
             */

            step: function () {

                this.$emit('step');

                this.$refs.analytics.step();

                if (Data.config.simulation.inoculation.active)
                    this.inoculate();

                //Each step do an attempt
                Data.nodes.forEach((node) => {
                    this.infectAttempt(node);
                    this.recoverAttempt(node);
                    this.killAttempt(node);
                });

            },

            //Infect Attempt
            infectAttempt: function (infected) {
                var $self = this;

                if ($self.allowed(infected, "mayInfect")) {
                    if (Data.config.simulation.infectBy === 'special')
                        specialInfect();
                    else
                        standardInfect();
                }

                function specialInfect() {

                    const K = Data.config.simulation.k;

                    var neighbors = Data.nodes.get(infected.neighbors, {
                        filter: function (node) {
                            return $self.allowed(node, "mayBeInfected");
                        }
                    });

                    if (neighbors.length == 0) return;

                    var target = _.sample(neighbors);

                    var equation = (1 - Math.exp(-K * neighbors.length));

                    if (Math.random() > Data.config.simulation[target.group].base.resist)
                        if (Math.random() > target.rate.resist)
                            if (Math.random() < equation * Data.config.simulation[infected.group].base.infect)
                                infect_ani(target);

                }

                function standardInfect() {

                    var neighbors = Data.nodes.get(infected.neighbors);
                    neighbors.forEach(eachNeighbors);

                    function eachNeighbors(target) {

                        if ($self.allowed(target, "mayBeInfected")) {
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

                            function infectByNode() {

                                if (Math.random() > Data.config.simulation[target.group].base.resist)
                                    if (Math.random() > target.rate.resist)
                                        if (Math.random() < infected.rate.infect * Data.config.simulation[infected.group].base.infect)
                                            infect_ani(target);
                            }

                            function infectByEdge() {

                                var edge = $self.filterEdgesByNodes(infected, target)[0];
                                if (edge == undefined) return;

                                if (Math.random() > Data.config.simulation[target.group].base.resist)
                                    if (Math.random() > target.rate.resist)
                                        if (Math.random() < edge.rate.infect * Data.config.simulation[infected.group].base.infect)
                                            infect_ani(target);
                            }

                            function infectByBoth() {

                                var edge = $self.filterEdgesByNodes(infected, target)[0];
                                if (edge == undefined) return;

                                if (Math.random() > Data.config.simulation[target.group].base.resist)
                                    if (Math.random() > target.rate.resist)
                                        if (Math.random() < infected.rate.infect * edge.rate.infect * Data.config.simulation[infected.group].base.infect)
                                            infect_ani(target);
                            }

                        }

                    }

                }

                function infect_ani(target) {

                    if ($self.mode('scientific'))
                        return $self.$emit('statusChange', target, Data.const.status.INFECTED);

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
                        draw: function () {
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
                        onUpdate: function () {
                            Data.network.redraw();
                        }
                    });

                    //Node expand animation
                    TweenMax.to(target, INFECT_EXPAND_TIME * Data.config.animation.scale, {
                        delay: INFECT_EXPAND_DELAY * Data.config.animation.scale,
                        size: target.base * INFECT_EXPAND_SCALE,
                        ease: Power2.easeOut,
                        onUpdate: function () {
                            Data.nodes.update(target);
                        },
                        onComplete: function () {
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
                                onUpdate: function () {
                                    Data.nodes.update(target);
                                }
                            });
                        }
                    });

                }

            },

            //Recover Attempt
            recoverAttempt: function (target) {
                var $self = this;

                if ($self.allowed(target, "mayRecover"))
                    doRecover();

                if ($self.allowed(target, "mayGetSusceptible"))
                    doSusceptible();

                function doRecover() {
                    if (Math.random() < target.rate.recover * Data.config.simulation[target.group].base.recover)
                        recover_ani(Data.const.status.RECOVERED);
                }

                function doSusceptible() {
                    if (Math.random() < target.rate.susceptible * Data.config.simulation[target.group].base.susceptible)
                        recover_ani(Data.const.status.SUSCEPTIBLE);
                }

                function recover_ani(status) {

                    if ($self.mode('scientific'))
                        return $self.$emit('statusChange', target, status);

                    const RECOVER_EXPAND_TIME = Number(Data.config.animation.recover.expandTime);
                    const RECOVER_EXPAND_SCALE = Number(Data.config.animation.recover.expandScale);
                    const RECOVER_RETRACT_TIME = Number(Data.config.animation.recover.retractTime);
                    const REST_TIME = RECOVER_EXPAND_TIME + RECOVER_RETRACT_TIME +
                            Number(Data.config.animation.recover.restTime); //2.4

                    $self.rest(target, REST_TIME);

                    TweenMax.to(target, RECOVER_EXPAND_TIME * Data.config.animation.scale, {
                        size: target.base * RECOVER_EXPAND_SCALE,
                        ease: Power2.easeOut,
                        onUpdate: function () {
                            Data.nodes.update(target);
                        },
                        onComplete: function () {

                            $self.$emit('statusChange', target, status);

                            TweenMax.to(target, RECOVER_RETRACT_TIME * Data.config.animation.scale, {
                                size: target.base,
                                ease: Elastic.easeOut.config(1, 0.3),
                                onUpdate: function () {
                                    Data.nodes.update(target);
                                }
                            });
                        }
                    });
                }
            },

            //Kill Attempt
            killAttempt: function (target) {
                var $self = this;

                if ($self.allowed(target, "mayDie"))
                    kill();

                function kill() {
                    if (Math.random() < target.rate.death * Data.config.simulation[target.group].base.death) {
                        death_ani();
                    }
                }

                function death_ani() {

                    if ($self.mode('scientific')) {
                        if (Data.config.simulation.d.birthWhenDie)
                            return $self.$emit('statusChange', target, Data.const.status.SUSCEPTIBLE);
                        else
                            return $self.$emit('statusChange', target, Data.const.status.DEATH);
                    }

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
                    if (!Data.config.simulation.d.birthWhenDie)
                        Data.edges.get(edges_id, {
                            filter: function (edge) {
                                return !edge.removing;
                            }
                        }).forEach(edge_ani);

                    //Node death animation
                    TweenMax.to(target, DEATH_RETRACT_TIME * Data.config.animation.scale, {
                        size: Data.config.vis.nodes.size * DEATH_RETRACT_SCALE,
                        ease: Power1.easeOut,
                        onUpdate: function () {
                            Data.nodes.update(target);
                        },
                        onComplete: function () {
                            //Birth animation
                            if (Data.config.simulation.d.birthWhenDie)
                                birth_ani();
                        }
                    });

                    function birth_ani() {
                        TweenMax.to(target, BIRTH_EXPAND_TIME * Data.config.animation.scale, {
                            size: target.base * BIRTH_EXPAND_SCALE,
                            ease: Power2.easeOut,
                            delay: BIRTH_EXPAND_DELAY * Data.config.animation.scale,
                            onUpdate: function () {
                                Data.nodes.update(target);
                            },
                            onComplete: function () {

                                $self.$emit('statusChange', target, Data.const.status.SUSCEPTIBLE);

                                TweenMax.to(target, BIRTH_RETRACT_TIME * Data.config.animation.scale, {
                                    size: target.base,
                                    ease: Elastic.easeOut.config(1, 0.3),
                                    onUpdate: function () {
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
                            onUpdate: function () {
                                Data.edges.update(edge);
                            },
                            onComplete: function () {
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
             * Inoculate
             */

            inoculate: function () {
                var $self = this;

                //Gets the number of vaccines remaining
                var left = Data.config.simulation.inoculation.limit - $self.shots;
                if (left <= 0) return;

                var quant = _.min([Data.config.simulation.inoculation.rate, left]);

                var nodes = Data.nodes.get({
                    filter: function (node) {
                        return $self.allowed(node, "mayBeVaccinated");
                    }
                });

                switch (Data.config.simulation.inoculation.by) {
                        //Method 1 - Random
                        //Just get random nodes and inoculate them
                    case 'random':
                        random();
                        break;
                        //Method 2 - Grades
                        //Sort all nodes by grades (the number of infected neighbors) and inoculate them
                    case 'grade':
                        grade();
                        break;
                        //Method 3 - Neighbor
                        //Sort all nodes by grades and inoculates their neighbors
                    case 'neighbor':
                        neighbor();
                        break;
                }

                function random() {
                    var samples = _.sampleSize(nodes, quant);
                    samples.forEach(vaccinate_ani);
                }

                function grade() {
                    var nodesSorted = sort(nodes);

                    //Takes some nodes according by configuration
                    var neighbors = _.take(nodesSorted, quant);

                    neighbors.forEach(vaccinate_ani);
                }

                function neighbor() {
                    var nodesSorted = sort(nodes);

                    //Flats the sorted nodes by their neighbors
                    var ids = _.flatMap(nodesSorted, function (n) {
                        return n.neighbors;
                    });

                    //Removes duplicates
                    ids = _.uniq(ids);

                    //Gets nodes by ids
                    var neighbors = Data.nodes.get(ids, {
                        filter: function (node) {
                            return $self.allowed(node, "mayBeVaccinated");
                        }
                    });

                    //Takes some nodes according by configuration
                    neighbors = _.take(neighbors, quant);

                    neighbors.forEach(vaccinate_ani);
                }

                //Sort by nodes that have the most infected neighbors
                function sort(nodes) {
                    return _.sortBy(nodes, [function (node) {
                        var quant = 0;

                        _.forEach(node.neighbors, function (id) {
                            var item = Data.nodes.get(id);
                            if ($self.isGroup(item, Data.const.status.INFECTED)) quant++;
                        });

                        return -quant;
                    }]);
                }

                function vaccinate_ani(target) {

                    $self.shots++;
                    if ($self.mode('scientific'))
                        return $self.$emit('statusChange', target, Data.const.status.VACCINATED);

                    const VACCINATE_EXPAND_TIME = Number(Data.config.animation.vaccinate.expandTime);
                    const VACCINATE_EXPAND_SCALE = Number(Data.config.animation.vaccinate.expandScale);
                    const VACCINATE_RETRACT_TIME = Number(Data.config.animation.vaccinate.retractTime);
                    const REST_TIME = VACCINATE_EXPAND_TIME + VACCINATE_RETRACT_TIME +
                            Number(Data.config.animation.vaccinate.restTime); //2.4

                    $self.rest(target, REST_TIME);

                    TweenMax.to(target, VACCINATE_EXPAND_TIME * Data.config.animation.scale, {
                        size: target.base * VACCINATE_EXPAND_SCALE,
                        ease: Power2.easeOut,
                        onUpdate: function () {
                            Data.nodes.update(target);
                        },
                        onComplete: function () {

                            $self.$emit('statusChange', target, Data.const.status.VACCINATED);

                            TweenMax.to(target, VACCINATE_RETRACT_TIME * Data.config.animation.scale, {
                                size: target.base,
                                ease: Power2.easeOut,
                                onUpdate: function () {
                                    Data.nodes.update(target);
                                }
                            });
                        }
                    });
                }

            },

            /**
             * Factories
             */

            factory: function () {
                var $self = this;

                Data.nodes = new vis.DataSet();
                Data.edges = new vis.DataSet();

                switch (Data.config.generator.method) {
                    case 'uniformFormat':
                        return $self.factoryUniformFormat();

                    case 'fullRandom':
                        return $self.factoryFullRandom();
                }

                return false;
            },

            factoryUniformFormat: function () {
                var $factory = Data.config.generator.factory.uniformFormat;

                Data.config.vis.physics.solver = 'repulsion';

                return {
                    nodes: nodesFactory(),
                    edges: edgesFactory()
                };

                function nodesFactory() {
                    var quant = Math.pow($factory.level, 2);

                    for (var i = 0; i < quant; i++)
                        Data.nodes.add({
                            id: i,
                            group: Data.const.status.SUSCEPTIBLE,
                            neighbors: [],
                            rate: $factory.node.rate
                        });

                    _.forEach($factory.node.groups, function (group) {
                        var quant = group.quant;
                        if (group.percent)
                            quant = group.quant / 100 * Data.nodes.length;

                        var samples = _.sampleSize(Data.nodes.get(), _.round(quant));
                        _.forEach(samples, (node) => {
                            node.group = group.ref;
                            Data.nodes.update(node);
                        });
                    });

                    return Data.nodes.get();
                }

                function edgesFactory() {
                    var edgeConfig = $factory.edge;
                    var row = $factory.level;
                    var line = $factory.level - 1;

                    for (var i = 0; i < row; i++)
                        for (var j = 0; j < line; j++) {
                            var node1 = Data.nodes.get(i * row + j);
                            var node2 = Data.nodes.get(i * row + (j + 1));

                            Data.edges.add({
                                from: node1.id,
                                to: node2.id,
                                rate: edgeConfig.rate
                            });

                            node1.neighbors.push(node2.id);
                            node2.neighbors.push(node1.id);

                            Data.nodes.update(node1);
                            Data.nodes.update(node2);
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

                            node1.neighbors.push(node2.id);
                            node2.neighbors.push(node1.id);

                            Data.nodes.update(node1);
                            Data.nodes.update(node2);
                        }

                    return Data.edges.get();
                }
            },

            factoryFullRandom: function () {
                var $factory = Data.config.generator.factory.fullRandom;

                return groupFactory();

                function groupFactory() {

                    var groups = [];
                    var quant = $factory.group.quant;

                    //Creates all Groups
                    for (var i = 0; i < quant; i++) {
                        var nodesQuant = _.random($factory.node.min, $factory.node.max);
                        var edgesQuant = _.random($factory.edge.min, $factory.edge.max);

                        var nodes = nodesFactory(nodesQuant);
                        var edges = edgesFactory(edgesQuant, nodes);

                        groups[i] = {
                            nodes: nodes,
                            edges: edges
                        };
                    }

                    //Connect Groups
                    for (var i = 0; i < groups.length; i++) {
                        var group1 = groups[i];
                        for (var j = i + 1; j < groups.length; j++) {
                            var group2 = groups[j];

                            var quant = _.random($factory.group.connections.min, $factory.group.connections.max);

                            while (quant > 0) {
                                var node1 = _.sample(group1.nodes);
                                var pool = group2.nodes;

                                do {
                                    var node2 = _.sample(group2.nodes);
                                    pool = _.without(pool, node2);

                                    if (addEdge(node1, node2, false)) {
                                        quant--;
                                        break;
                                    }
                                } while (!_.isEmpty(pool)); //Attempt limit

                                if (_.isEmpty(pool)) break;
                            }

                        }
                    }

                    //Apply Starting Values
                    if (!$factory.group.startingValuesByGroup.enabled)
                        applyStartingValues(Data.nodes.get());
                    else {
                        var samples = _.sampleSize(groups, $factory.group.startingValuesByGroup.quant)
                        _.forEach(samples, (group) => {
                            applyStartingValues(group.nodes);
                        });
                    }

                    return {
                        nodes: Data.nodes.get(),
                        edges: Data.edges.get()
                    };

                    //Scan each node group and apply the quantity
                    function applyStartingValues(nodes) {
                        _.forEach($factory.node.groups, function (group) {
                            var quant = group.quant;
                            if (group.percent)
                                quant = group.quant / 100 * nodes.length;

                            var samples = _.sampleSize(nodes, _.round(quant));
                            _.forEach(samples, (node) => {
                                node.group = group.ref;
                                Data.nodes.update(node);
                            });
                        });
                    }
                }

                function nodesFactory(quant) {
                    var ids = [];
                    var rate = $factory.node.rate;

                    for (var i = 0; i < quant; i++) {
                        var id = Data.nodes.length;
                        ids.push(id);

                        Data.nodes.add({
                            id: id,
                            group: Data.const.status.SUSCEPTIBLE,
                            neighbors: [],
                            rate: {
                                infect: _.random(rate.infect.min, rate.infect.max, true),
                                resist: _.random(rate.resist.min, rate.resist.max, true),
                                recover: _.random(rate.recover.min, rate.recover.max, true),
                                susceptible: _.random(rate.susceptible.min, rate.susceptible.max, true),
                                death: _.random(rate.death.min, rate.death.max, true)
                            }
                        });

                    }

                    return Data.nodes.get(ids);
                }

                function edgesFactory(quant, nodes) {

                    while (quant > 0) {
                        var changed = false;
                        nodes.forEach(function (node1) {
                            if (quant <= 0) return;
                            var pool = nodes;
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

                    return Data.edges.get();
                }

                function addEdge(node1, node2, limited = true) {
                    /**
                     * Not Allowed:
                     *  - Node1 and Node 2 are the same node
                     *  - Node1 and Node 2 are neighbors
                     *  - Node1's neighbors length are greater than the max edges configured per nodes
                     *  - Node2's neighbors length are greater than the max edges configured per nodes
                     */
                    if (node1.id === node2.id) return false;
                    if (_.includes(node1.neighbors, node2.id)) return false;
                    if (_.includes(node2.neighbors, node1.id)) return false;
                    if (limited) {
                        if (node1.neighbors.length >= $factory.edge.density) return false;
                        if (node2.neighbors.length >= $factory.edge.density) return false;
                    }

                    Data.edges.add({
                        from: node1.id,
                        to: node2.id,
                        rate: {
                            infect: _.random($factory.edge.rate.infect.min, $factory.edge.rate.infect.max, true)
                        }
                    });

                    node1.neighbors.push(node2.id);
                    node2.neighbors.push(node1.id);

                    Data.nodes.update(node1);
                    Data.nodes.update(node2);

                    return true;
                }

            }

        }
    }
</script>