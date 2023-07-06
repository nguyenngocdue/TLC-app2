function init() {

    // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
    // For details, see https://gojs.net/latest/intro/buildingObjects.html
    const $ = go.GraphObject.make;  // for conciseness in defining templates
    myDiagram =
        new go.Diagram("myDiagramDiv",
            {
                allowMove: false,
                allowCopy: false,
                allowDelete: false,
                allowHorizontalScroll: false,
                layout:
                    $(go.TreeLayout,
                        {
                            alignment: go.TreeLayout.AlignmentStart,
                            angle: 0,
                            compaction: go.TreeLayout.CompactionNone,
                            layerSpacing: 16,
                            layerSpacingParentOverlap: 1,
                            nodeIndentPastParent: 1.0,
                            nodeSpacing: 0,
                            setsPortSpot: false,
                            setsChildPortSpot: false
                        })
            });

    myDiagram.nodeTemplate =
        $(go.Node,
            { // no Adornment: instead change panel background color by binding to Node.isSelected
                selectionAdorned: false,
                // a custom function to allow expanding/collapsing on double-click
                // this uses similar logic to a TreeExpanderButton
                // doubleClick: (e, node) => {
                //   var cmd = myDiagram.commandHandler;
                //   if (node.isTreeExpanded) {
                //     if (!cmd.canCollapseTree(node)) return;
                //   } else {
                //     if (!cmd.canExpandTree(node)) return;
                //   }
                //   e.handled = true;
                //   if (node.isTreeExpanded) {
                //     cmd.collapseTree(node);
                //   } else {
                //     cmd.expandTree(node);
                //   }
                // }
            },
            $("TreeExpanderButton",
                {
                    width: 14,
                    "ButtonBorder.fill": "whitesmoke",
                    "ButtonBorder.stroke": "lightgray",
                    "_buttonFillOver": "rgba(0,128,255,0.25)",
                    "_buttonStrokeOver": null,
                    "_buttonFillPressed": "rgba(0,128,255,0.4)"
                }),
            $(go.Panel, "Horizontal",
                { position: new go.Point(16, 0), margin: new go.Margin(0, 20, 0, 0), defaultAlignment: go.Spot.Center },
                new go.Binding("background", "isSelected", s => s ? "lightblue" : "white").ofObject(),
                // $("TriStateCheckBoxButton"),
                $(go.TextBlock,
                    { font: '9pt Verdana, sans-serif', margin: new go.Margin(0, 0, 0, 2) },
                    new go.Binding("text", "name"))
            )  // end Horizontal Panel
        );  // end Node

    // without lines
    //myDiagram.linkTemplate = $(go.Link);

    // with lines
    myDiagram.linkTemplate =
        $(go.Link,
            {
                selectable: false,
                routing: go.Link.Orthogonal,
                fromEndSegmentLength: 4,
                toEndSegmentLength: 4,
                fromSpot: new go.Spot(0.001, 1, 7, 0),
                toSpot: go.Spot.Left
            },
            $(go.Shape,
                { stroke: 'gray', strokeDashArray: [1, 2] }));

    // console.log(nodeDataArray);
    myDiagram.model = new go.TreeModel(nodeDataArray);
}

init()

// window.addEventListener('DOMContentLoaded', init);
// console.log("AAA")
// console.log(nodeDataArray);
