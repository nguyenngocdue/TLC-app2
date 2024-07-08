function init(id, zoomToFit, nodeDataArray) {

  const maxW = 300;
  const maxH = 160;
  const avatarWidth = 100;
  const tlcBlue = "#081d7d";
  const highlighted = "#b60612";
  // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
  // For details, see https://gojs.net/latest/intro/buildingObjects.html
  const $ = go.GraphObject.make;  // for conciseness in defining templates

  // some constants that will be reused within templates
  // var mt8 = new go.Margin(8, 0, 0, 0);
  // var mr8 = new go.Margin(0, 8, 0, 0);
  // var ml8 = new go.Margin(0, 0, 0, 8);
  var roundedRectangleParams = {
    parameter1: 2,  // set the rounded corner
    spot1: go.Spot.TopLeft, spot2: go.Spot.BottomRight  // make content go all the way to inside edges of rounded corners
  };

  const myDiagram =
    new go.Diagram("myDiagramDiv_" + id,  // the DIV HTML element
      {
        "undoManager.isEnabled": true,
        // Put the diagram contents at the top center of the viewport
        initialDocumentSpot: go.Spot.Top,
        initialViewportSpot: go.Spot.Top,
        // OR: Scroll to show a particular node, once the layout has determined where that node is
        // "InitialLayoutCompleted": e => {
        //  var node = e.diagram.findNodeForKey(28);
        //  if (node !== null) e.diagram.commandHandler.scrollToPart(node);
        // },
        initialScale: 0.7, // Set the initial scale
        layout:
          $(go.TreeLayout,  // use a TreeLayout to position all of the nodes
            {
              isOngoing: false,  // don't relayout when expanding/collapsing panels
              treeStyle: go.TreeLayout.StyleLastParents,
              // properties for most of the tree:
              angle: 90,
              layerSpacing: 80,
              // properties for the "last parents":
              alternateAngle: 0,
              alternateAlignment: go.TreeLayout.AlignmentStart,
              alternateNodeIndent: 15,
              alternateNodeIndentPastParent: 1,
              alternateNodeSpacing: 15,
              alternateLayerSpacing: 40,
              alternateLayerSpacingParentOverlap: 1,
              alternatePortSpot: new go.Spot(0.001, 1, 20, 0),
              alternateChildPortSpot: go.Spot.Left,
            })
      });

  const modernNode = () => {
    return $(go.Panel, "Spot",
      $(go.Shape, "RoundedRectangle",
        { fill: tlcBlue, stroke: null, width: maxW, height: maxH, },
        new go.Binding("fill", "isHighlighted", h => h ? highlighted : tlcBlue).ofObject(),
      ),
      $(go.Panel, "Auto", { alignment: new go.Spot(0.5, 0), },
        $(go.Panel, "Spot", { scale: 1 },
          $(go.Shape, "Circle", { fill: "white", width: avatarWidth, strokeWidth: 6, stroke: tlcBlue }),
          $(go.Panel, "Spot",
            { isClipping: true },
            $(go.Shape, "Circle", { width: avatarWidth, strokeWidth: 0 }),
            $(go.Picture, { width: avatarWidth, height: avatarWidth }, new go.Binding("source", "avatar",),)
          )
        )
      ),
      $(go.Panel, "Auto", { alignment: new go.Spot(0.93, 0.1), },
        $(go.Panel, "Spot", { scale: 1 },

          $(go.TextBlock, { stroke: "white", font: "bold 10pt sans-serif", text: "alignment: Center", textAlign: "center" },
            new go.Binding("text", "memberCount",),
          )
        )
      ),
      $(go.Panel, "Vertical",
        $(go.Panel, "Vertical", { margin: 10, width: maxW - 10, },
          $(go.TextBlock, "", { stroke: "white" },),
          $(go.TextBlock, "", { stroke: "white" },),
          $(go.TextBlock, { stroke: "white", font: "bold 16pt sans-serif", text: "alignment: Center", textAlign: "center" },
            new go.Binding("text", "name",)
          ),
          $(go.TextBlock, { stroke: "white" },
            new go.Binding("text", "employeeidAndWorkplace",),
          ),
        ),
        $(go.Panel, "Vertical", { width: maxW - 10, },
          $(go.TextBlock, { stroke: "white", font: "bold 12pt sans-serif", text: "alignment: Center", textAlign: "center" },
            new go.Binding("text", "title",),
          ),
          // $(go.TextBlock, { stroke: "white" },
          //   new go.Binding("text", "email",/* email => "Email: " + email*/),
          // ),
          // $(go.TextBlock, { stroke: "white" },
          //   new go.Binding("text", "phone",/* phone => "Phone: " + phone*/),
          // ),
        ),
      ),
    )
  }

  // define the Node template
  myDiagram.nodeTemplate =
    $(go.Node, "Auto",
      {
        locationSpot: go.Spot.Top,
        isShadowed: true,
        cursor: "pointer",

        shadowBlur: 1,
        shadowOffset: new go.Point(0, 1),
        shadowColor: "rgba(0, 0, 0, .14)",

        selectionAdornmentTemplate:  // selection adornment to match shape of nodes
          $(go.Adornment, "Auto",
            $(go.Shape, "RoundedRectangle", roundedRectangleParams,
              { fill: null, stroke: "#7986cb", strokeWidth: 3 }
            ),
            $(go.Placeholder)
          ), // end Adornment

        click: function (e, obj) {
          window.open(obj.part.data.url, '_blank');
        },

        toolTip:  // define a tooltip for each node that displays the color as text
          $("ToolTip",
            $(go.TextBlock, { margin: 4 },
              new go.Binding("text", "key"))
          )  // end of Adornment
      },
      modernNode(),
      // classicalNode(),
    );

  // define the Link template, a simple orthogonal line
  myDiagram.linkTemplate =
    $(go.Link, go.Link.Orthogonal,
      { corner: 5, selectable: false },
      $(go.Shape, { strokeWidth: 3, stroke: "#424242" }));  // dark gray, rounded corner links

  // create the Model with data for the tree, and assign to the Diagram
  myDiagram.model =
    new go.TreeModel(
      {
        nodeParentKeyProperty: "parent",  // this property refers to the parent node data
        nodeDataArray: nodeDataArray
      });

  // Overview
  myOverview =
    new go.Overview("myOverviewDiv_" + id,  // the HTML DIV element for the Overview
      { observed: myDiagram, contentAlignment: go.Spot.Center });   // tell it which Diagram to show and pan
  // myDiagram.toolManager.panningTool.isEnabled = false;
  if (zoomToFit) {
    myDiagram.commandHandler.zoomToFit();
    myDiagram.commandHandler.decreaseZoom(0.85);
  } else {
    // myDiagram.commandHandler.decreaseZoom(0.5);
  }

  // var diagramContainer = document.getElementById('myDiagramDiv_' + id);
  // diagramContainer.style.overflow = 'hidden';
}

// var currentResultsIndex = -1;
// the Search functionality highlights all of the nodes that have at least one data property match a RegExp
function searchDiagram(id) {  // called by button
  const myDiagram = go.Diagram.fromDiv("myDiagramDiv_" + id);
  var input = document.getElementById("mySearch_" + id);
  if (!input) return;
  myDiagram.focus();

  myDiagram.startTransaction("highlight search");

  if (input.value) {
    // search four different data properties for the string, any of which may match for success
    // create a case insensitive RegExp from what the user typed
    var safe = input.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    var regex = new RegExp(safe, "i");
    var results = myDiagram.findNodesByExample(
      { name: regex },
      { email: regex },
      { employeeid: regex });
    myDiagram.highlightCollection(results);
    // try to center the diagram at the first node that was found
    if (results.count > 0) myDiagram.centerRect(results.first().actualBounds);
  } else {  // empty string only clears highlighteds collection
    myDiagram.clearHighlighteds();
  }

  myDiagram.commitTransaction("highlight search");
}
// window.addEventListener('DOMContentLoaded', init(1));
