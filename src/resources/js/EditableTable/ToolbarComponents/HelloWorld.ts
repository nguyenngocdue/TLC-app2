import { TableParams } from '../Type/EditableTable3ParamType'

export class HelloWorld {
    private params: TableParams
    constructor(params: TableParams) {
        this.params = params
    }
    render() {
        return `Hello World ${this.params.tableName}`
    }
}
