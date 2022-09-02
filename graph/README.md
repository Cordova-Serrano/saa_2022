
# Graph API

## Install requirements

Make sure you are in the graph folder and  Python installed.

```powershell
cd graph

pip install -r requirements.txt
```

## Run mock server

```bash
uvicorn graph_sample:graphAPI
```

You will be able to access the mock server and test the API at `http://127.0.0.1:8000/docs`
