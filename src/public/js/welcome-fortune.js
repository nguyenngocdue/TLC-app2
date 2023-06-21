
var $ = go.GraphObject.make;
const diagram =
  new go.Diagram("diagramDiv",  // create a Diagram for the HTML Div element
    { "undoManager.isEnabled": true });  // enable undo & redo

function showPoint(loc) {
  var docloc = diagram.transformDocToView(loc);
  var elt = document.getElementById("Message1");
  elt.textContent = "Selected node location,\ndocument coordinates: " + loc.x.toFixed(2) + " " + loc.y.toFixed(2) +
    "\nview coordinates: " + docloc.x.toFixed(2) + " " + docloc.y.toFixed(2);
}

diagram.nodeTemplate =
  $(go.Node, "Auto",
    {
      click: (e, obj) => showPoint(obj.part.location),
    },
    new go.Binding("location", "loc", go.Point.parse),
    $(go.Shape, "RoundedRectangle", { fill: "lightgray" }),
    $(go.TextBlock, { margin: 5 },
      new go.Binding("text", "key"))
  );

diagram.linkTemplate =
  $(go.Link,
    { curve: go.Link.Bezier },  // Bezier curve
    $(go.Shape),
    $(go.Shape, { toArrow: "Standard" })
  );

var nodeDataArray = [
  { key: "Alpha", loc: "0 0" },
  { key: "Beta", loc: "0 100" }
];
var linkDataArray = [
  { from: "Alpha", to: "Beta" },
  { from: "Alpha", to: "Beta" },
  { from: "Alpha", to: "Beta" },
  { from: "Beta", to: "Alpha" },
  { from: "Beta", to: "Beta" },
];
diagram.model = new go.GraphLinksModel(nodeDataArray, linkDataArray);
