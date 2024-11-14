import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { PickerDateTime4Edit } from './PickerDateTime4Edit'
import { PickerDateTime4View } from './PickerDateTime4View'

export class PickerDateTime4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new PickerDateTime4Edit(this.params).render()
            default:
                return new PickerDateTime4View(this.params).render()
        }
    }
}
