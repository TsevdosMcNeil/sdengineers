const del = require('del');

console.log(`Deleting...`);
// directory path
const dir = './dist';
//file path
const zipFIle = './codigo.tar.gz';

// delete directory recursively
(async () => {
    try {
        await del(dir);

        console.log(`${dir} is deleted!`);
    } catch (err) {
        console.error(`Error while deleting ${dir}.`);
    }
})();

//Check if zip file exists and delete
(async () => {
    try {
        await del(zipFIle);

        console.log(`${zipFIle} is deleted!`);
    } catch (err) {
        console.error(`Error while deleting ${zipFIle}.`);
    }
})();
