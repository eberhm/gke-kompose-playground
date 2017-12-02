# Testing GKE and kompose

# Resources
https://kubernetes.io/docs/tools/kompose/user-guide/

https://cloud.google.com/community/tutorials/developing-services-with-k8s

https://cloud.google.com/container-registry/docs/pushing-and-pulling

https://kubernetes.io/docs/tutorials/stateless-application/expose-external-ip-address/



# Project Setup

Go to project directory and build the images

```bash
docker-compose -f docker-compose.build.yml build
```

Push images to your gke cluster's registry

https://cloud.google.com/container-registry/docs/pushing-and-pulling

```bash
gcloud docker -- push gcr.io/<your-project-id>/kompose-fpm:1.1
gcloud docker -- push gcr.io/<your-project-id>/kompose-web:1.1
gcloud docker -- push gcr.io/<your-project-id>/kompose-db:1.1
```

Kompose app

```bash
kompose up
```

In GKE expose the web pod with load balancer option

# Testing

Get the public IP with

```bash
kubectl get services
```

Yous should see a LoadBalancer. Get the external ip as the your_gke_lb_ip

## GET (collection)

`curl http://<your_gke_lb_ip>:8080/app_dev.php/notes -H 'Content-Type: application/json' -w "\n"`

## GET (single item with id 1)

`curl http://<your_gke_lb_ip>:8080/app_dev.php/notes/1 -H 'Content-Type: application/json' -w "\n"`

## POST (insert)

`curl -X POST http://<your_gke_lb_ip>:8080/app_dev.php/notes -d '{"note":"Hello World!"}' -H 'Content-Type: application/json' -w "\n"`

## PUT (update)

`curl -X PUT http://<your_gke_lb_ip>:8080/app_dev.php/notes/1 -d '{"note":"Uhauuuuuuu!"}' -H 'Content-Type: application/json' -w "\n"`

## DELETE

`curl -X DELETE http://<your_gke_lb_ip>:8080/app_dev.php/notes/1 -H 'Content-Type: application/json' -w "\n"`

## Debugging

To get into a container

```bash
kubectl get pods
```

```bash
kubectl exec -it <pod-id> bash
```