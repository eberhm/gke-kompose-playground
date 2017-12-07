# Testing GKE, kompose and Teleprecense

This playgroud pretends to illustrate how you can use GKE, Kompose and teleprecense to have a local environment that allows you to have the non code related services in the GKE while having the application service in local and therefore edit the code as in a full local environment. 


# Resources

https://deis.com/blog/2016/first-kubernetes-cluster-gke/

https://cloud.google.com/community/tutorials/developing-services-with-k8s

https://cloud.google.com/container-registry/docs/pushing-and-pulling

https://kubernetes.io/docs/tools/kompose/user-guide/

https://kubernetes.io/docs/tutorials/stateless-application/expose-external-ip-address/

https://www.telepresence.io/



# Project Setup

Provided you have already a GKE account setup, a project and a cluster already created (if you do not have any of those check https://deis.com/blog/2016/first-kubernetes-cluster-gke/). Go to project directory and build the images

```bash
docker-compose -f docker-compose.local.yml build
```

Push images to your gke cluster's registry

https://cloud.google.com/container-registry/docs/pushing-and-pulling

```bash
gcloud docker -- push gcr.io/<your-project-id>/kompose-fpm
gcloud docker -- push gcr.io/<your-project-id>/kompose-nginx
gcloud docker -- push gcr.io/<your-project-id>/kompose-db
```

Kompose app

Login into gcloud

```bash
gcloud container clusters get-credentials <cluster-name> --zone <cluster-zone>
gcloud auth application-default login
```

Deploy app in the cluster
```bash
kompose -f docker-kompose.yml up
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

# Local development with Teleprecense

Install teleprecense https://www.telepresence.io/

Run teleprecense

```bash
telepresence --swap-deployment fpm --expose 9000:9000 --run-shell
```

This will log you in a new shell. From it just run

```bash
docker-compose -f docker-compose.local.yml up -d fpm
```

Note: You should have your application installed locally so you must need to do ```composer install```

# Trobleshooting

To get into a container

```bash
kubectl get pods
```

```bash
kubectl exec -it <pod-id> bash
```