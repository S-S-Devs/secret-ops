document.getElementById('current-date').textContent = 'Fecha actual: ' + new Date().toLocaleDateString();
document.getElementById('last-modified').textContent = 'Última modificación: ' + document.lastModified;

const browserInfo = navigator.userAgent;
let verOffset, browserName, fullVersion;

if ((verOffset = browserInfo.indexOf("Chrome")) != -1) {
    browserName = "Chrome";
    fullVersion = browserInfo.substring(verOffset + 7);
} else if ((verOffset = browserInfo.indexOf("Firefox")) != -1) {
    browserName = "Firefox";
    fullVersion = browserInfo.substring(verOffset + 8);
} else if ((verOffset = browserInfo.indexOf("MSIE")) != -1) {
    browserName = "Internet Explorer";
    fullVersion = browserInfo.substring(verOffset + 5);
} else if ((verOffset = browserInfo.indexOf("Safari")) != -1) {
    browserName = "Safari";
    fullVersion = browserInfo.substring(verOffset + 7);
    if ((verOffset = browserInfo.indexOf("Version")) != -1) {
        fullVersion = browserInfo.substring(verOffset + 8);
    }
} else {
    browserName = "Unknown";
    fullVersion = "Unknown";
}

document.getElementById('browser-info').textContent = `Navegador: ${browserName}, Versión: ${fullVersion.split(' ')[0]}`;