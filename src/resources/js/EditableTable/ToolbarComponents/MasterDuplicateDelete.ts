import { twMerge } from 'tailwind-merge'
import { ToolbarComponentParent } from './ToolbarComponentParent'

export class MasterDuplicateDelete extends ToolbarComponentParent {
    applyPostRenderScript() {
        // add event listener
        const { tableName } = this.params
        $(`#${tableName}__btnMasterDuplicate`).on('click', this.onClickDuplicate.bind(this))
        $(`#${tableName}__btnMasterTrash`).on('click', this.onClickDelete.bind(this))
    }

    onClickDuplicate() {
        console.log('onClickDuplicate')
    }

    onClickDelete() {
        console.log('onClickDelete')
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
