<div id="sample">
    <!-- The DIV for the Diagram needs an explicit size or else we won't see anything.
         This also adds a border to help see the edges of the viewport. -->
    <div id="myDiagramDiv1" class="w-full" style="border: 1px solid black; height: 300px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
        <canvas tabindex="0" width="398" height="398" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 398px; height: 398px;">
            This text is displayed if your browser does not support the Canvas HTML element.
        </canvas>
        <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
            <div style="position: absolute; width: 1px; height: 1px;">The JS script has syntax error.</div>
        </div>
    </div>
</div>

<script id="code1">
  const myDiagram1 = new go.Diagram("myDiagramDiv1", { "undoManager.isEnabled": true });
  
//   myDiagram1.nodeTemplate =
//   $(go.Node, "Auto",
//     { locationSpot: go.Spot.Center },
//     new go.Binding("location", "loc", go.Point.parse),
//     $(go.Shape, "RoundedRectangle", { fill: "lightgray" }),
//     $(go.TextBlock, { margin: 5 }, new go.Binding("text", "title"))
//   );

  // define a simple Node template
  myDiagram1.nodeTemplate =
    new go
        .Node("Auto")  // the Shape will go around the TextBlock
        .add(
            new go.Shape("RoundedRectangle",{ strokeWidth: 0, fill: "white" })  // no border; default fill is white
            .bind("fill", "bg-color")
            )  // Shape.fill is bound to Node.data.color
        .add(
            new go.TextBlock({ margin: 8, font: "bold 14px sans-serif", stroke: '#333' })  // some room around the text
            .bind("text", "title").bind("stroke", 'color')
            )
        ;  // TextBlock.text is bound to Node.data.key
  
  // but use the default Link template, by not setting Diagram.linkTemplate
//   myDiagram1.linkTemplate =
//   $(go.Link,
//     { curve: go.Link.Bezier },  // Bezier curve
//     $(go.Shape),
//     $(go.Shape, { toArrow: "Standard" })
//   );
  
  // create the model data that will be represented by Nodes and Links
  myDiagram1.model = new go.GraphLinksModel(
    @json($blocks),
    @json($links))
    
  </script>