module.exports = window.factoryUniformFormat = function(config, nodes, edges) {

    return {
        nodes: nodesFactory().get(),
        edges: edgesFactory().get()
    };

    function nodesFactory() {
        var nodeConfig = config.factory.node;

        for (var i = 0; i < Math.pow(config.factory.level, 2); i++)
            nodes.add({
                id: i,
                group: "s",
                rate: nodeConfig.rate
            });

        //Scan each node group and apply the quantity
        _.forEach(nodeConfig.groups, function(group) {
            for (var i = 0; i < group.quant; i++) {
                var node = _.sample(nodes.get());
                node.group = group.ref;
                nodes.update(node);
            }
        });

        return nodes;
    }

    function edgesFactory() {
        var edgeConfig = config.factory.edge;
        var row = config.factory.level;
        var line = config.factory.level - 1;

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
