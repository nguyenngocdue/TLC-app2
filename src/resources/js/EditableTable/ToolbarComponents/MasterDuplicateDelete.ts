import { twMerge } from 'tailwind-merge'
import { ToolbarComponentParent } from './ToolbarComponentParent'
import swal from 'sweetalert'

export class MasterDuplicateDelete extends ToolbarComponentParent {
    applyPostRenderScript() {
        // add event listener
        const { tableName } = this.params
        $(`#${tableName}__btnMasterDuplicate`).on('click', this.onClickDuplicate.bind(this))
        $(`#${tableName}__btnMasterTrash`).on('click', this.onClickDelete.bind(this))
    }

    getSelectedIds() {
        return this.dataSource.data.filter((line) => line._checkbox_for_line_)
    }

    onClickDuplicate() {
        const listOfId = this.getSelectedIds()
        console.log('onClickDuplicate', listOfId)
        swal({
            icon: 'info',
            title: 'Duplicating',
            text: `Are you sure you want to duplicate the ${listOfId.length} selected item${
                listOfId.length == 1 ? '' : 's'
            }?`,
            buttons: ['Cancel', 'Duplicate'],
            dangerMode: false,
        })
    }

    onClickDelete() {
        const listOfId = this.getSelectedIds()
        console.log('onClickDelete', listOfId)
        swal({
            icon: 'warning',
            title: 'Deleting',
            text: `Are you sure you want to delete the ${listOfId.length} selected item${
                listOfId.length == 1 ? '' : 's'
            }?`,
            buttons: ['Cancel', 'Delete'],
            dangerMode: true,
        })
    }

    render() {
        const { tableConfig } = this.params
        const classList = twMerge(
            tableConfig.classList?.button || '',
            'px-2 py-1 rounded text-white gap-0.5 text-xs',
        )
        return `<div id="${this.params.tableName}__master_button_group" class="hidden">
            <button id="${this.params.tableName}__btnMasterDuplicate" class="${classList} bg-blue-500 hover:bg-blue-700" type="button">
                <i class="fa fa-copy mr-1"></i>
                Duplicate
            </button>
            <button id="${this.params.tableName}__btnMasterTrash" class="${classList} bg-red-500 hover:bg-red-700" type="button">
                <i class="fa fa-trash mr-1"></i>
                Trash
            </button>
        </div>`
    }
}
