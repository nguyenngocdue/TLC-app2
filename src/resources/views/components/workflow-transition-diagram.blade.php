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
  
  var $ = go.GraphObject.make;
  const myDiagram1 = new go.Diagram("myDiagramDiv1", {
     "undoManager.isEnabled": true ,
    //  layout: $(go.LayeredDigraphLayout, { alignOption: go.LayeredDigraphLayout.AlignAll })
    //  layout: $(go.GridLayout,{ comparer: go.GridLayout.smartComparer })
    //  layout:  $(go.TreeLayout,{ angle: 90, nodeSpacing: 10, layerSpacing: 30 })
  });

  myDiagram1.grid.visible = true;
  myDiagram1.toolManager.draggingTool.isGridSnapEnabled = true;
  myDiagram1.toolManager.resizingTool.isGridSnapEnabled = true;

  const showPoint = (e, obj)=>{
    const location =obj.part.location

    const locStr = location.x.toFixed(2) + " " + location.y.toFixed(2)
    console.log(obj, obj.part, locStr)
    // console.log(location.x.toFixed(2), location.y.toFixed(2));
  }
  
  myDiagram1.nodeTemplate =
  $(go.Node, 
    {locationSpot: go.Spot.Center, },
    "Auto",
    {
      mouseLeave: (e, obj) => showPoint(e, obj),
      // click: (e, obj) => showPoint(obj.part.location),
    },
    new go.Binding("location", "location", go.Point.parse),
    $(go.Shape, 
      "RoundedRectangle", 
      { strokeWidth: 0, fill: "lightgray" },
      new go.Binding("fill", "bg-color")
      ),
    $(go.TextBlock, 
      { margin: 5 },
      new go.Binding("text", "title"),
      new go.Binding("stroke", "color"),
      new go.Binding("index", "color"),
      )
  );
  myDiagram1.linkTemplate =
  $(go.Link,
    { curve: go.Link.Bezier },  // Bezier curve
    $(go.Shape),
    $(go.Shape, { toArrow: "Standard" })
  );
  
  // create the model data that will be represented by Nodes and Links
  myDiagram1.model = new go.GraphLinksModel(
    @json($blocks),
    @json($links))
    
  </script>