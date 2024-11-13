import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { Number4Edit } from './Number4Edit'
import { Number4View } from './Number4View'

export class Number4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (this.params.column.mode) {
            case 'edit':
                return new Number4Edit(this.params).render()
            default:
                return new Number4View(this.params).render()
        }
    }
}
