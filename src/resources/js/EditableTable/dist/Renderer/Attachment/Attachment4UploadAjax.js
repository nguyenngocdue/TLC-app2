"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.attachment4UploadFileAjax = void 0;
const Attachment4UploadWithProgress_1 = require("./Attachment4UploadWithProgress");
const attachment4UploadFileAjax = (tableConfig, file, fieldName, groupId, dataLine, onProgress) => __awaiter(void 0, void 0, void 0, function* () {
    if (!file)
        return;
    const formData = new FormData();
    // const envConfig = tableConfig.envConfig || {}
    formData.append('object_type', tableConfig.entityLineType || 'no-entityLineType');
    formData.append('object_id', dataLine['id'] || 'no-objectId');
    formData.append(`${fieldName}[toBeUploaded][${groupId}][]`, file);
    const url = tableConfig.uploadServiceEndpoint || 'no-endpoint';
    return (0, Attachment4UploadWithProgress_1.uploadFileWithProgress)(url, formData, onProgress);
});
exports.attachment4UploadFileAjax = attachment4UploadFileAjax;
//# sourceMappingURL=Attachment4UploadAjax.js.map