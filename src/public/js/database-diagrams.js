function init(nodeDataArray, linkDataArray) {

  let lastMouseEnteredNode = null
  const tableIndex = {}

  const updateLocation = (model) => {
    const array = model.nodeDataArray
    const index = tableIndex[lastMouseEnteredNode]
    console.log(array[index])
  }
  const showModel = () => {
    document.getElementById("mySavedModel").textContent = myDiagram.model.toJson()
    const model = JSON.parse(myDiagram.model.toJson())
    updateLocation(model)
  }
  const indexTable = () => {
    for (let i = 0; i < nodeDataArray.length; i++) {
      tableIndex[nodeDataArray[i]['key']] = i
    }
    console.log("Index", tableIndex)
  }

  // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
  // For details, see https://gojs.net/latest/intro/buildingObjects.html
  const $ = go.GraphObject.make;  // for conciseness in defining templates


  myDiagram =
    new go.Diagram("myDiagramDiv",
      {
        validCycle: go.Diagram.CycleNotDirected,  // don't allow loops
        // For this sample, automatically show the state of the diagram's model on the page
        "ModelChanged": e => {
          if (e.isTransactionFinished) showModel();
        },
        "undoManager.isEnabled": true,
      });

  // myDiagram.grid.visible = true;
  myDiagram.toolManager.draggingTool.isGridSnapEnabled = true;
  myDiagram.toolManager.resizingTool.isGridSnapEnabled = true;

  // myDiagram.addDiagramListener("SelectionMoved", e => console.log("SelectionMoved" + e));
  // This template is a Panel that is used to represent each item in a Panel.itemArray.
  // The Panel is data bound to the item object.
  var fieldTemplate =
    $(go.Panel, "TableRow",  // this Panel is a row in the containing Table
      new go.Binding("portId", "name"),  // this Panel is a "port"
      {
        background: "transparent",  // so this port's background can be picked by the mouse
        fromSpot: go.Spot.LeftRightSides,  // links only go from the right side to the left side
        toSpot: go.Spot.LeftRightSides,
        // allow drawing links from or to this port:
        fromLinkable: true, toLinkable: true
      },
      // $(go.Shape,
      //   {
      //     width: 12, height: 12, column: 0, strokeWidth: 2, margin: 4,
      //     // but disallow drawing links from or to this shape:
      //     fromLinkable: false, toLinkable: false
      //   },
      //   new go.Binding("figure", "figure"),
      //   new go.Binding("fill", "color")),
      $(go.TextBlock,
        {
          margin: new go.Margin(0, 5), column: 1, font: "bold 13px sans-serif",
          alignment: go.Spot.Left,
          // and disallow drawing links from or to this text:
          fromLinkable: false, toLinkable: false
        },
        new go.Binding("text", "name")),
      $(go.TextBlock,
        { margin: new go.Margin(0, 5), column: 2, font: "13px sans-serif", alignment: go.Spot.Left },
        new go.Binding("text", "info")),
      $(go.TextBlock,
        { margin: new go.Margin(0, 5), column: 3, font: "13px sans-serif", alignment: go.Spot.Left },
        new go.Binding("text", "null")),
      $(go.TextBlock,
        { margin: new go.Margin(0, 5), column: 0, font: "13px sans-serif", alignment: go.Spot.Left },
        new go.Binding("text", "key")),
      // $(go.TextBlock,
      //   { margin: new go.Margin(0, 5), column: 5, font: "13px sans-serif", alignment: go.Spot.Left },
      //   new go.Binding("text", "default")),
      // $(go.TextBlock,
      //   { margin: new go.Margin(0, 5), column: 6, font: "13px sans-serif", alignment: go.Spot.Left },
      //   new go.Binding("text", "extra")),
    );

  // This template represents a whole "record".
  myDiagram.nodeTemplate =
    $(go.Node, "Auto",
      {
        copyable: false,
        deletable: false,
        // click: (e, o) => console.log("Clicked", e, o),
        mouseEnter: (e, o) => {
          lastMouseEnteredNode = o.key
          // console.log("mouseEntered", lastMouseEnteredNode, tableIndex[lastMouseEnteredNode])
        }
      },
      new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
      // this rectangular shape surrounds the content of the node
      $(go.Shape,
        { fill: "#EEEEEE" }),
      // the content consists of a header and a list of items
      $(go.Panel, "Vertical",
        // this is the header for the whole node
        $(go.Panel, "Auto",
          { stretch: go.GraphObject.Horizontal },  // as wide as the whole node
          $(go.Shape,
            { fill: "#1570A6", stroke: null }),
          $(go.TextBlock,
            {
              alignment: go.Spot.Center,
              margin: 3,
              stroke: "white",
              textAlign: "center",
              font: "bold 12pt sans-serif"
            },
            new go.Binding("text", "key"))),
        // this Panel holds a Panel for each item object in the itemArray;
        // each item Panel is defined by the itemTemplate to be a TableRow in this Table
        $(go.Panel, "Table",
          {
            padding: 2,
            minSize: new go.Size(100, 10),
            defaultStretch: go.GraphObject.Horizontal,
            itemTemplate: fieldTemplate
          },
          new go.Binding("itemArray", "fields")
        )  // end Table Panel of items
      )  // end Vertical Panel
    );  // end Node

  myDiagram.linkTemplate =
    $(go.Link,
      {
        relinkableFrom: true, relinkableTo: true, // let user reconnect links
        toShortLength: 4, fromShortLength: 2,
        routing: go.Link.Orthogonal, corner: 6,
        curve: go.Link.JumpOver,
      },
      $(go.Shape, { strokeWidth: 1.5 }),
      $(go.Shape, { toArrow: "Standard", stroke: null })
    );

  myDiagram.model =
    new go.GraphLinksModel(
      {
        copiesArrays: true,
        copiesArrayObjects: true,
        linkFromPortIdProperty: "fromPort",
        linkToPortIdProperty: "toPort",
        nodeDataArray,
        linkDataArray,
      });

  showModel();  // show the diagram's initial model
  indexTable(); // index the table key to number

}
window.addEventListener('DOMContentLoaded', () => init(nodeDataArray, linkDataArray));