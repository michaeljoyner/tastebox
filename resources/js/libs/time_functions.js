function minutesForHumans(mins) {
    const days = Math.floor(mins / (60 * 24));
    const hrs = Math.floor((mins % (60 * 24)) / 60);
    const mns = mins % 60;

    let string = "";

    if (days) {
        string = string.concat(`${days}day`);
    }

    if (hrs) {
        string = string.concat(` ${hrs}hr`);
    }

    if (mns) {
        string = string.concat(` ${mns}min`);
    }

    return string ? string : "0mins";
}

const isRecent = (timestamp, minutes) => {
    if (!timestamp) {
        return false;
    }
    const now = new Date().getTime();

    return now - timestamp <= minutes * 60 * 1000;
};

const dateForInputFormat = (date) => {
    if (!date) {
        return new Date().toLocaleDateString("en-CA");
    }

    return date.toLocaleDateString("en-CA");
};
export { minutesForHumans, isRecent, dateForInputFormat };
