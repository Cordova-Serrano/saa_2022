
# Graph API

## Install requirements

Make sure you are in the graph folder and  Python installed.

### Windows

TODO

### Linux

```bash
cd graph

python -m venv .venv

source .venv/bin/activate

pip install -r requirements.txt

deactivate
```

## Run mock server

```bash
source .venv/bin/activate
uvicorn graph_sample:graphAPI
```

You will be able to access the mock server and test the API at `http://127.0.0.1:8000/docs`

Make sure to deactivate the virtual environment when you are done

```bash
deactivate
```