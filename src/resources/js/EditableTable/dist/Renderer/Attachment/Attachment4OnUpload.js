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
exports.addOnUploadListener = void 0;
const Thumbnail4View_1 = require("../Thumbnail/Thumbnail4View");
const Attachment4UploadAjax_1 = require("./Attachment4UploadAjax");
const addOnUploadListener = (inputElement, controlId, tableConfig, fieldName, groupId, dataLine) => {
    inputElement.addEventListener('change', (e) => __awaiter(void 0, void 0, void 0, function* () {
        const files = e.target.files;
        // console.log(fieldName, groupId)
        if (files) {
            for (let i = 0; i < files.length; i++) {
                const imgContainerId = `${controlId}__${i}__img_container`;
                const emptyBox = Thumbnail4View_1.Thumbnail4View.renderThumbnailBox(`${controlId}__${i}`).outerHTML;
                const div = `<div id="${imgContainerId}">${emptyBox}</div>`;
                $(`#${controlId}_thumbnail_div`).append(div);
                const onProgress = (percent) => {
                    const id = `#${controlId}__${i}__progress_bar`;
                    $(id).css('width', `${percent}%`);
                    // console.log(id, percent)
                    // console.log('Upload progress:', file.name, percent)
                };
                const onDone = (result) => {
                    const id = document.querySelector(`#${imgContainerId}`);
                    const obj = Object.values(result)[0];
                    const item = {
                        src: 'https://minio.tlcmodular.com/tlc-app/' + obj.url_thumbnail,
                        name: obj.filename,
                    };
                    if (id && id.parentElement) {
                        id.parentElement.append(Thumbnail4View_1.Thumbnail4View.renderThumbnailBox(item));
                        //remove the img_container
                        $(`#${imgContainerId}`).remove();
                    }
                };
                try {
                    (0, Attachment4UploadAjax_1.attachment4UploadFileAjax)(tableConfig, files[i], fieldName, groupId, dataLine, onProgress).then((result) => {
                        onDone(result);
                    });
                }
                catch (e) {
                    console.error('Upload error:', e);
                }
            }
        }
    }));
};
exports.addOnUploadListener = addOnUploadListener;
//# sourceMappingURL=Attachment4OnUpload.js.map