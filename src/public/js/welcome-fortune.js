// This button assumes data binding to the "checked" property.
// go.GraphObject.defineBuilder("TriStateCheckBoxButton", args => {
//   var button = /** @type {Panel} */ (
//     go.GraphObject.make("Button",
//       {
//         "ButtonBorder.fill": "white",
//         width: 14,
//         height: 14
//       },
//       go.GraphObject.make(go.Shape,
//         {
//           name: "ButtonIcon",
//           geometryString: 'M0 0 M0 8.85 L4.9 13.75 16.2 2.45 M16.2 16.2',  // a 'check' mark
//           strokeWidth: 2,
//           stretch: go.GraphObject.Fill,  // this Shape expands to fill the Button
//           geometryStretch: go.GraphObject.Uniform,  // the check mark fills the Shape without distortion
//           background: null,
//           visible: false  // visible set to false: not checked, unless data.checked is true
//         },
//         new go.Binding("visible", "checked", p => p === true || p === null),
//         new go.Binding("stroke", "checked", p => p === null ? null : "black"),
//         new go.Binding("background", "checked", p => p === null ? "gray" : null)
//       )
//     )
//   );

//   function updateCheckBoxesDown(node, val) {
//     node.diagram.model.setDataProperty(node.data, "checked", val);
//     node.findTreeChildrenNodes().each(child => updateCheckBoxesDown(child, val))
//   }

//   function updateCheckBoxesUp(node) {
//     var parent = node.findTreeParentNode();
//     if (parent !== null) {
//       var anychecked = parent.findTreeChildrenNodes().any(n => n.data.checked !== false && n.data.checked !== undefined);
//       var allchecked = parent.findTreeChildrenNodes().all(n => n.data.checked === true);
//       node.diagram.model.setDataProperty(parent.data, "checked", (allchecked ? true : (anychecked ? null : false)));
//       updateCheckBoxesUp(parent);
//     }
//   }

//   button.click = (e, button) => {
//     if (!button.isEnabledObject()) return;
//     var diagram = e.diagram;
//     if (diagram === null || diagram.isReadOnly) return;
//     if (diagram.model.isReadOnly) return;
//     e.handled = true;
//     var shape = button.findObject("ButtonIcon");
//     diagram.startTransaction("checkbox");
//     // Assume the name of the data property is "checked".
//     var node = button.part;
//     var oldval = node.data.checked;
//     var newval = (oldval !== true);  // newval will always be either true or false, never null
//     // Set this data.checked property and those of all its children to the same value
//     updateCheckBoxesDown(node, newval);
//     // Walk up the tree and update all of their checkboxes
//     updateCheckBoxesUp(node);
//     // support extra side-effects without clobbering the click event handler:
//     if (typeof button["_doClick"] === "function") button["_doClick"](e, button);
//     diagram.commitTransaction("checkbox");
//   };

//   return button;
// });

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

  // create a random tree
  // var nodeDataArray = [{ key: 0 }];
  // var max = 25;
  // var count = 0;
  // while (count < max) {
  //   count = makeTree(3, count, max, nodeDataArray, nodeDataArray[0]);
  // }
  // console.log(nodeDataArray);
  // nodeDataArray = [
  //   { key: 0, },
  //   { key: 1, parent: 0 },
  //   { key: "AA", parent: 0 },
  //   { key: "345", parent: "AA" },
  // ]

  console.log(nodeDataArray);
  myDiagram.model = new go.TreeModel(nodeDataArray);
}

// function makeTree(level, count, max, nodeDataArray, parentdata) {
//   var numchildren = Math.floor(Math.random() * 10);
//   for (var i = 0; i < numchildren; i++) {
//     if (count >= max) return count;
//     count++;
//     var childdata = { key: count, parent: parentdata.key, value: 123 };
//     nodeDataArray.push(childdata);
//     if (level > 0 && Math.random() > 0.5) {
//       count = makeTree(level - 1, count, max, nodeDataArray, childdata);
//     }
//   }
//   return count;
// }
window.addEventListener('DOMContentLoaded', init);
