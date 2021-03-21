import emitter from "tiny-emitter/instance";

const eventHub = {
    $on: (...args) => emitter.on(...args),
    $emit: (...args) => emitter.emit(...args),
};

export { eventHub };
