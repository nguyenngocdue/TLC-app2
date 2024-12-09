"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.CustomFunction4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
class CustomFunction4View extends Renderer4View_1.Renderer4View {
    control() {
        const { customRenderFn } = this;
        if (customRenderFn) {
            return customRenderFn();
        }
        return 'custom renderer function is undefined';
    }
}
exports.CustomFunction4View = CustomFunction4View;
//# sourceMappingURL=CustomFunction4View.js.map