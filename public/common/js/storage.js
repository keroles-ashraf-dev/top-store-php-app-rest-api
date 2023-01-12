
const mStorage = localStorage;

function set(key, value) {
	mStorage.setItem(key, value);
}

function get(key) {
	return mStorage.getItem(key);
}

function remove(key) {
	mStorage.removeItem(key);
}

function clear() {
	mStorage.clear;
}

export {
	set,
	get,
	remove,
	clear,
}