import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { Dropdown4Edit } from './Dropdown4Edit'
import { Dropdown4View } from './Dropdown4View'

export class Dropdown4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (this.params.column.mode) {
            case 'edit':
                return new Dropdown4Edit(this.params).render()
            default:
                return new Dropdown4View(this.params).render()
        }
    }
}
