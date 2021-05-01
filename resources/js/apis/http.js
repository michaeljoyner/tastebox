function post(url, body) {
    return new Promise((resolve, reject) => {
        axios
            .post(url, body)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function get(url) {
    return new Promise((resolve, reject) => {
        axios
            .get(url)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function del(url) {
    return new Promise((resolve, reject) => {
        axios
            .delete(url)
            .then(resolve)
            .catch(({ response }) => reject(response));
    });
}

function upload(url, file, onProgress, upload_name = "image") {
    return new Promise((resolve, reject) => {
        const req = new XMLHttpRequest();
        const fd = new FormData();
        fd.append(upload_name, file);
        fd.append("_token", document.querySelector("#csrf-token-meta").content);

        req.upload.addEventListener("progress", (ev) => {
            onProgress(Math.round((ev.loaded / ev.total) * 100));
        });

        req.addEventListener("load", (ev) => {
            if (req.status && req.status >= 200 && req.status <= 399) {
                return resolve({ status: req.status, data: req.response });
            }

            return reject({ status: req.status, data: null });
        });

        req.addEventListener("error", (ev) => {
            return reject({ status: null, data: null });
        });

        req.addEventListener("abort", (ev) => {
            return reject({ status: null, data: null });
        });

        req.open("POST", url);
        req.responseType = "json";
        req.send(fd);
    });
}

export { get, post, del, upload };
