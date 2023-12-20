@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')


{{-- <table id="myTable" border="1">
    <tr>
      <th>Field 1</th>
      <th>Field 2</th>
      <th>Field 3</th>
    </tr>
    <tr>
      <td class="editable-cell" tabindex="0">Static Text 1</td>
      <td class="editable-cell" tabindex="0">Static Text 2</td>
      <td class="editable-cell" tabindex="0">Static Text 3</td>
    </tr>
  </table>
  
  <script>
    const editableCells = document.querySelectorAll('.editable-cell');
  
    editableCells.forEach(cell => {
      cell.addEventListener('keydown', function(event) {
        if (event.key === 'Tab' && !event.shiftKey) {
          makeEditable.call(this);
        } else if (event.key === 'Tab' && event.shiftKey) {
          rewindFocus.call(this);
        }
      });
      cell.addEventListener('focus', makeEditable);
    });
  
    function makeEditable() {
      const currentValue = this.textContent;
      this.innerHTML = `<input type="text" value="${currentValue}" />`;
  
      const inputElement = this.querySelector('input');
      inputElement.focus();
  
      inputElement.addEventListener('blur', function() {
        const newValue = this.value;
        this.parentNode.innerHTML = newValue;
      });
    }
  
    function rewindFocus() {
      let index = Array.from(editableCells).indexOf(this);
      if (index > 0) {
        editableCells[index - 1].focus();
      } else {
        editableCells[editableCells.length - 1].focus();
      }
    }
  </script> --}}
  
{{-- 

<table id="myTable" border="1">
    <tr>
      <th>Field 1</th>
      <th>Field 2</th>
      <th>Field 3</th>
    </tr>
    <tr>
      <td class="editable-cell" tabindex="0">Option 1</td>
      <td class="editable-cell" tabindex="0">Option 2</td>
      <td class="editable-cell" tabindex="0">Option 3</td>
    </tr>
  </table>
  
  <script>
    const editableCells = document.querySelectorAll('.editable-cell');
  
    editableCells.forEach(cell => {
      cell.addEventListener('keydown', function(event) {
        if (event.key === 'Tab' && !event.shiftKey) {
          makeEditable.call(this);
        } else if (event.key === 'Tab' && event.shiftKey) {
          rewindFocus.call(this);
        }
      });
      cell.addEventListener('focus', makeEditable);
    });
  
    function makeEditable() {
      const currentValue = this.textContent;
  
      const selectElement = document.createElement('select');
      const option1 = document.createElement('option');
      option1.value = 'Option 1';
      option1.textContent = 'Option 1';
      const option2 = document.createElement('option');
      option2.value = 'Option 2';
      option2.textContent = 'Option 2';
      const option3 = document.createElement('option');
      option3.value = 'Option 3';
      option3.textContent = 'Option 3';
  
      selectElement.appendChild(option1);
      selectElement.appendChild(option2);
      selectElement.appendChild(option3);
  
      selectElement.value = currentValue;
  
      this.innerHTML = '';
      this.appendChild(selectElement);
  
      selectElement.focus();
  
      selectElement.addEventListener('blur', function() {
        const newValue = this.value;
        this.parentNode.innerHTML = newValue;
      });
    }
  
    function rewindFocus() {
      let index = Array.from(editableCells).indexOf(this);
      if (index > 0) {
        editableCells[index - 1].focus();
      } else {
        editableCells[editableCells.length - 1].focus();
      }
    }
  </script> --}}

<div id="divMain"></div>

<script src="{{ asset('js/editable-table.js') }}"></script>
<script>EditableTableDemo()</script>

@endsection 