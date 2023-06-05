async function fetchWithRedirect(method, url, body) {
    const res = await (await fetch(url, {
        method: method,
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    })).json()
    console.log(res)

    window.location.replace(res.redirectUrl);
}
