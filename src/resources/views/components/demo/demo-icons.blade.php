<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Widgets">
        {{-- <div class="grid gap-6 mb-8 md:grid-cols-2 "> --}}
       <x-renderer.table maxH={{null}} :columns="$columns" :dataSource="$dataSource" groupBy="name" groupByLength=2 showNo=1/>
        {{-- </div> --}}
    </x-renderer.card>
   
</div>

<script>

async function setClipboardValue(text) {
    try {
        await navigator.clipboard.writeText(text);
        console.log('Text set to clipboard');
    } catch (error) {
        console.error('Failed to set clipboard text: ', error);
    }
}

// Usage
// const textToSet = 'This is the text to set in the clipboard.';
// setClipboardValue(textToSet);

function insertSubstring(originalString, substring, position) {
  if (position < 0 || position > originalString.length) {
    return originalString; // Invalid position, return the original string
  }

  const part1 = originalString.substring(0, position);
  const part2 = originalString.substring(position);

  return part1 + substring + part2;
}
</script>