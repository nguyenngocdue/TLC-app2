import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { Checkbox4Edit } from './Checkbox4Edit'
import { Checkbox4View } from './Checkbox4View'

export class Checkbox4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (this.params.column.mode) {
            case 'edit':
                return new Checkbox4Edit(this.params).render()
            default:
                return new Checkbox4View(this.params).render()
        }
    }
}
